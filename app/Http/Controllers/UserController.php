<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
