<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDocument;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->latest()->get();

        return view('users.index', [
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:255',
            'role' => 'required|in:foreman,driver,labour',
            'status' => 'required|boolean',
            'salary' => 'required|numeric|min:0',
        ]);

        $user = User::create([
            'name' => $request->name,
            'password' => Hash::make('12345678'),
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'status' => $request->boolean('status'),
            'salary' => $request->salary,
        ]);

        return response()->json(['message' => 'User created successfully', 'data' => $user], 201);
    }

    public function downloadExcelTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Users');

        $headers = ['Name', 'Email', 'Phone', 'Salary', 'Role', 'Status'];
        foreach ($headers as $index => $header) {
            $sheet->setCellValue([$index + 1, 1], $header);
        }

        $sampleRows = [
            ['Ahmed Ali', 'ahmed.ali@example.com', '+971500000001', 3500, 'labour', 'Active'],
            ['Sara Khan', 'sara.khan@example.com', '+971500000002', 4200, 'driver', 'Active'],
            ['John Smith', 'john.smith@example.com', '', 5000, 'foreman', 'Inactive'],
        ];

        foreach ($sampleRows as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                $sheet->setCellValue([$colIndex + 1, $rowIndex + 2], $value);
            }
        }

        foreach (range('A', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $tempPath = storage_path('app/users-import-template.xlsx');
        $writer->save($tempPath);

        return response()->download($tempPath, 'users-import-template.xlsx')->deleteFileAfterSend(true);
    }

    public function importExcel(Request $request)
    {
        set_time_limit(300);
        ini_set('max_execution_time', '300');
        ini_set('memory_limit', '512M');

        $request->validate([
            'file' => 'required|file|max:5120',
        ]);

        $uploaded = $request->file('file');
        $extension = strtolower($uploaded->getClientOriginalExtension() ?: '');
        if (!in_array($extension, ['xlsx', 'xls', 'csv'], true)) {
            throw ValidationException::withMessages([
                'file' => 'The file must be an Excel or CSV file (.xlsx, .xls, .csv).',
            ]);
        }

        $tempDir = storage_path('app/imports');
        File::ensureDirectoryExists($tempDir);
        $tempPath = $tempDir.DIRECTORY_SEPARATOR.uniqid('users-', true).'.'.$extension;

        try {
            File::copy($uploaded->getPathname(), $tempPath);

            $reader = IOFactory::createReaderForFile($tempPath);
            if (method_exists($reader, 'setReadDataOnly')) {
                $reader->setReadDataOnly(true);
            }
            $spreadsheet = $reader->load($tempPath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, false, false);
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);
            throw ValidationException::withMessages([
                'file' => $this->excelImportErrorMessage($e),
            ]);
        } finally {
            if (is_file($tempPath)) {
                File::delete($tempPath);
            }
        }

        if (count($rows) < 2) {
            throw ValidationException::withMessages([
                'file' => 'The Excel file has no data rows.',
            ]);
        }

        $headerMap = $this->mapUserExcelHeaders($rows[0] ?? []);
        if (!isset($headerMap['name']) || !isset($headerMap['email']) || !isset($headerMap['role'])) {
            throw ValidationException::withMessages([
                'file' => 'Excel must include "Name", "Email", and "Role" headers.',
            ]);
        }

        $existingEmails = array_flip(User::pluck('email')->map(fn ($email) => strtolower((string) $email))->all());
        $seenEmails = [];
        $payloads = [];
        $errors = [];
        $now = now();
        $defaultPassword = Hash::make('12345678');

        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            if ($this->excelRowIsEmpty($row)) {
                continue;
            }

            $excelRow = $i + 1;
            $name = trim((string) ($row[$headerMap['name']] ?? ''));
            $email = strtolower(trim((string) ($row[$headerMap['email']] ?? '')));
            $phone = isset($headerMap['phone']) ? trim((string) ($row[$headerMap['phone']] ?? '')) : '';
            $salaryRaw = isset($headerMap['salary']) ? trim((string) ($row[$headerMap['salary']] ?? '')) : '';
            $role = $this->normalizeImportedRole($row[$headerMap['role']] ?? '');
            $status = $this->normalizeImportedStatus($row[$headerMap['status']] ?? 'Active');
            $rowErrors = [];

            if ($name === '') {
                $rowErrors[] = 'Name is required.';
            }

            if ($email === '') {
                $rowErrors[] = 'Email is required.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $rowErrors[] = 'Email is invalid.';
            } elseif (isset($existingEmails[$email]) || isset($seenEmails[$email])) {
                $rowErrors[] = 'Email already exists.';
            }

            if (!$role) {
                $rowErrors[] = 'Role must be foreman, driver, or labour.';
            }

            $salary = $salaryRaw === '' ? 0 : $salaryRaw;
            if (!is_numeric($salary) || (float) $salary < 0) {
                $rowErrors[] = 'Salary must be a number of 0 or more.';
            }

            if ($rowErrors) {
                $errors[] = "Row {$excelRow}: " . implode(' ', $rowErrors);
                continue;
            }

            $seenEmails[$email] = true;
            $payloads[] = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone !== '' ? $phone : null,
                'role' => $role,
                'status' => $status ? 1 : 0,
                'salary' => round((float) $salary, 2),
                'password' => $defaultPassword,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (empty($payloads)) {
            throw ValidationException::withMessages([
                'file' => $errors ? implode(' ', $errors) : 'No valid user rows were found in the Excel file.',
            ]);
        }

        $this->dropUsersNameUniqueIndex();

        try {
            $createdUsers = DB::transaction(function () use ($payloads) {
                foreach (array_chunk($payloads, 200) as $chunk) {
                    User::insert($chunk);
                }

                $created = collect();
                foreach (array_chunk(array_column($payloads, 'email'), 200) as $emailChunk) {
                    $created = $created->merge(User::whereIn('email', $emailChunk)->get());
                }

                return $created->values();
            });
        } catch (QueryException $e) {
            report($e);
            throw ValidationException::withMessages([
                'file' => $this->excelImportErrorMessage($e),
            ]);
        }

        return response()->json([
            'message' => $createdUsers->count() . ' user(s) imported successfully.',
            'data' => $createdUsers,
            'errors' => $errors,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:255',
            'role' => 'required|in:foreman,driver,labour',
            'status' => 'required|boolean',
            'salary' => 'required|numeric|min:0',
            'password' => 'nullable|string|min:8',
        ]);

        $payload = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'status' => $request->boolean('status'),
            'salary' => $request->salary,
        ];

        if ($request->filled('password')) {
            $payload['password'] = Hash::make($request->password);
        }

        $user->update($payload);

        return response()->json(['message' => 'User updated successfully', 'data' => $user]);
    }

    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return response()->json(['message' => 'You cannot delete your own account.'], 422);
        }

        foreach ($user->documents as $document) {
            $this->deleteDocumentFile($document->file_path);
        }

        File::deleteDirectory(dirname($this->userDocumentsDir($user)));
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function documents(User $user)
    {
        return response()->json([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'folder' => $this->userDocumentsFolderName($user),
                ],
                'documents' => $user->documents()->latest()->get()->map(fn (UserDocument $document) => $this->formatDocument($document)),
            ],
        ]);
    }

    public function storeDocuments(Request $request, User $user)
    {
        $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'file|mimes:jpg,jpeg,png,webp,gif,pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $documents = [];

        foreach ($request->file('files', []) as $file) {
            $stored = $this->storeDocumentFile($file, $user);
            $documents[] = $this->formatDocument($user->documents()->create($stored));
        }

        return response()->json([
            'message' => 'Document uploaded successfully',
            'data' => $documents,
        ], 201);
    }

    public function destroyDocument(User $user, UserDocument $document)
    {
        if ($document->user_id !== $user->id) {
            return response()->json(['message' => 'Document not found.'], 404);
        }

        $this->deleteDocumentFile($document->file_path);
        $document->delete();

        return response()->json([
            'message' => 'Document deleted successfully',
        ]);
    }

    private function mapUserExcelHeaders(array $headerRow): array
    {
        $map = [];

        foreach ($headerRow as $index => $header) {
            $key = strtolower(trim((string) $header));
            $key = preg_replace('/\s+/', ' ', $key);

            if (in_array($key, ['name', 'full name', 'full_name'], true)) {
                $map['name'] = $index;
            } elseif ($key === 'email') {
                $map['email'] = $index;
            } elseif (in_array($key, ['phone', 'phone number', 'mobile'], true)) {
                $map['phone'] = $index;
            } elseif ($key === 'salary') {
                $map['salary'] = $index;
            } elseif ($key === 'role') {
                $map['role'] = $index;
            } elseif ($key === 'status') {
                $map['status'] = $index;
            }
        }

        return $map;
    }

    private function normalizeImportedRole(mixed $value): ?string
    {
        $role = strtolower(trim((string) $value));

        return in_array($role, ['foreman', 'driver', 'labour'], true) ? $role : null;
    }

    private function normalizeImportedStatus(mixed $value): bool
    {
        $status = strtolower(trim((string) $value));

        if (in_array($status, ['0', 'inactive', 'no', 'false', 'disabled'], true)) {
            return false;
        }

        return true;
    }

    private function excelRowIsEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }

    private function userDocumentsFolderName(User $user): string
    {
        $slug = Str::slug($user->name) ?: 'user';

        return $slug . '_' . $user->id;
    }

    private function userDocumentsRelativeDir(User $user): string
    {
        return 'user-details/' . $this->userDocumentsFolderName($user) . '/files';
    }

    private function userDocumentsDir(User $user): string
    {
        return public_path($this->userDocumentsRelativeDir($user));
    }

    private function storeDocumentFile($file, User $user): array
    {
        $storageDir = $this->userDocumentsDir($user);
        File::ensureDirectoryExists($storageDir);

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $fileName = Str::uuid()->toString() . ($extension ? '.' . $extension : '');
        $file->move($storageDir, $fileName);

        return [
            'file_path' => $this->userDocumentsRelativeDir($user) . '/' . $fileName,
            'original_name' => $file->getClientOriginalName(),
        ];
    }

    private function deleteDocumentFile(?string $path): void
    {
        if (!$path) {
            return;
        }

        $absolutePath = public_path($path);
        if (File::exists($absolutePath)) {
            File::delete($absolutePath);
        }
    }

    private function dropUsersNameUniqueIndex(): void
    {
        foreach (Schema::getIndexes('users') as $index) {
            $columns = $index['columns'] ?? [];
            $isNameUnique = ($index['unique'] ?? false)
                && !($index['primary'] ?? false)
                && $columns === ['name'];

            if (!$isNameUnique) {
                continue;
            }

            Schema::table('users', function ($table) use ($index) {
                $table->dropUnique($index['name']);
            });
        }
    }

    private function excelImportErrorMessage(\Throwable $e): string
    {
        $message = $e->getMessage();

        if (str_contains($message, 'ZipArchive')) {
            return 'The server cannot read .xlsx files. Please install PHP zip support, or upload a .csv file.';
        }

        if (str_contains($message, 'users_name_unique') || (str_contains($message, 'Duplicate entry') && str_contains($message, 'name'))) {
            return 'A unique name rule is still on the database. Run migrations, then try again.';
        }

        if (str_contains($message, 'users_email_unique') || (str_contains($message, 'Duplicate entry') && str_contains($message, 'email'))) {
            return 'One or more emails already exist. Remove duplicate emails and try again.';
        }

        return 'Unable to import the Excel file. Please check the file and try again.';
    }

    private function formatDocument(UserDocument $document): array
    {
        $name = $document->original_name ?: $document->file_path;
        $isPdf = str_ends_with(strtolower($name), '.pdf');
        $isImage = (bool) preg_match('/\.(jpe?g|png|webp|gif)$/i', $name);

        return [
            'id' => $document->id,
            'name' => $document->original_name,
            'url' => asset($document->file_path),
            'is_pdf' => $isPdf,
            'is_image' => $isImage,
        ];
    }
}
