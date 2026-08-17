@extends('layouts.dashboard')

@section('title', 'Users')

@section('style')

<link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        .users-content {
        padding: 1.3rem;
        }

        .users-card {
        border-radius: 16px;
        border: 1px solid rgba(8, 0, 89, 0.08);
        background: rgba(255, 255, 255, 0.72);
        backdrop-filter: blur(10px);
        box-shadow: 0 12px 30px rgba(8, 0, 89, 0.08);
        padding: 1rem;
        }

        .users-title {
        color: #080059;
        font-weight: 700;
        margin: 0;
        }

        .users-subtitle {
        color: #6f7294;
        margin: 0;
        font-size: 0.9rem;
        }

        .btn-add-user {
        border: none;
        border-radius: 12px;
        background: linear-gradient(120deg, #eabc73, #f1d19a);
        color: #080059;
        font-weight: 700;
        padding: 0.62rem 0.95rem;
        transition: all 0.3s ease;
        box-shadow: 0 10px 22px rgba(234, 188, 115, 0.35);
        }

        .btn-add-user:hover {
        transform: translateY(-2px);
        color: #080059;
        box-shadow: 0 15px 26px rgba(234, 188, 115, 0.45);
        }

        .table-chip {
        border-radius: 999px;
        padding: 0.33rem 0.62rem;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        }

        .role-admin { background: rgba(8, 0, 89, 0.1); color: #080059; }
        .role-manager { background: rgba(39, 130, 255, 0.14); color: #2d66ba; }
        .role-tech { background: rgba(234, 188, 115, 0.24); color: #7a5500; }

        .status-active { background: rgba(28, 166, 115, 0.16); color: #1f7a58; }
        .status-inactive { background: rgba(220, 76, 100, 0.14); color: #b13a50; }
        .error-message {
        font-size: 0.8rem;
        display: block;
        margin-top: 0.2rem;
        }

        .action-btn {
        width: 34px;
        height: 34px;
        border: 1px solid rgba(8, 0, 89, 0.16);
        border-radius: 10px;
        background: #fff;
        color: #080059;
        transition: all 0.25s ease;
        margin-right: 0.3rem;
        }

        .action-btn:hover {
        transform: translateY(-2px);
        border-color: #eabc73;
        color: #eabc73;
        box-shadow: 0 10px 18px rgba(8, 0, 89, 0.12);
        }

        .action-btn.folder-user:hover {
        color: #2874d9;
        border-color: #2874d9;
        }

        .action-btn.delete-user:hover {
        color: #d94b6e;
        border-color: #d94b6e;
        }

        .user-folder-path {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        flex-wrap: wrap;
        font-size: 0.82rem;
        color: #6f7294;
        background: rgba(8, 0, 89, 0.04);
        border: 1px solid rgba(8, 0, 89, 0.08);
        border-radius: 10px;
        padding: 0.55rem 0.75rem;
        margin-bottom: 1rem;
        }

        .user-folder-path i { color: #eabc73; }
        .user-folder-path strong { color: #080059; font-weight: 700; }

        .btn-upload-docs {
        border: none;
        border-radius: 12px;
        background: linear-gradient(120deg, #eabc73, #f1d19a);
        color: #080059;
        font-weight: 700;
        padding: 0.55rem 0.9rem;
        }

        .user-folder-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 0.85rem;
        min-height: 180px;
        }

        .user-folder-empty {
        grid-column: 1 / -1;
        text-align: center;
        color: #6f7294;
        padding: 2.5rem 1rem;
        border: 1px dashed rgba(8, 0, 89, 0.16);
        border-radius: 14px;
        background: rgba(8, 0, 89, 0.02);
        }

        .user-file-card {
        border: 1px solid rgba(8, 0, 89, 0.1);
        border-radius: 12px;
        background: #fff;
        padding: 0.55rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        }

        .user-file-preview {
        height: 110px;
        border-radius: 10px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9ff;
        border: 1px solid rgba(8, 0, 89, 0.08);
        text-decoration: none;
        color: inherit;
        }

        .user-file-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        }

        .user-file-preview.is-pdf { background: rgba(220, 76, 100, 0.06); color: #b13a50; font-size: 2rem; }
        .user-file-preview.is-file { color: #080059; font-size: 2rem; }

        .user-file-name {
        font-size: 0.78rem;
        font-weight: 600;
        color: #080059;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin: 0;
        }

        .user-file-delete {
        width: 100%;
        border: 1px solid rgba(220, 76, 100, 0.25);
        border-radius: 8px;
        background: rgba(220, 76, 100, 0.08);
        color: #b13a50;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.35rem 0.4rem;
        }

        .user-file-delete:hover {
        background: rgba(220, 76, 100, 0.16);
        }

        .user-folder-error {
        font-size: 0.8rem;
        color: #b13a50;
        min-height: 1.1rem;
        }

        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
        border: 1px solid rgba(8, 0, 89, 0.2);
        border-radius: 10px;
        min-height: 36px;
        background: #fff;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.page-item.active .page-link {
        background: #080059;
        border-color: #080059;
        color: #fff;
        }

        .dataTables_wrapper .dataTables_paginate .page-link {
        color: #080059;
        }

        .users-modal .modal-content {
        border-radius: 16px;
        border: 1px solid rgba(8, 0, 89, 0.09);
        box-shadow: 0 22px 48px rgba(8, 0, 89, 0.26);
        overflow: hidden;
        }

        .users-modal .modal-header {
        border-bottom: 1px solid rgba(8, 0, 89, 0.08);
        background: linear-gradient(130deg, #080059, #1c109f);
        color: #fff;
        }

        .users-modal .modal-title {
        font-size: 1rem;
        font-weight: 700;
        }

        .users-modal .form-control,
        .users-modal .form-select {
        border-radius: 10px;
        border: 1px solid rgba(8, 0, 89, 0.16);
        min-height: 42px;
        }

        .users-modal .form-control:focus,
        .users-modal .form-select:focus {
        border-color: #eabc73;
        box-shadow: 0 0 0 0.2rem rgba(234, 188, 115, 0.22);
        }

        .users-modal .btn-save {
        background: linear-gradient(120deg, #eabc73, #f2d39e);
        color: #080059;
        border: none;
        font-weight: 700;
        }

        .users-modal.fade .modal-dialog {
        transform: translateY(25px) scale(0.98);
        transition: transform 0.35s ease, opacity 0.35s ease;
        }

        .users-modal.show .modal-dialog {
        transform: translateY(0) scale(1);
        }

        @media (max-width: 767.98px) {
        .users-content {
            padding: 0.95rem;
        }

        .users-card {
            padding: 0.8rem;
        }
        }
    </style>
@endsection

@section('content')
<section class="users-content">
    <div class="users-card">
      <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div>
          <h5 class="users-title">User Management</h5>
          <p class="users-subtitle">Manage system users and permissions</p>
        </div>
        <button class="btn btn-add-user" id="openAddUser">
          <i class="fa-solid fa-user-plus me-1"></i>Add User
        </button>
      </div>

      <div class="table-responsive">
        <table id="usersTable" class="table align-middle mb-0">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Salary</th>
              <th>Role</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($users as $user)
            <tr data-user-id="{{ $user->id }}" data-user-name="{{ e($user->name) }}">
              <td>{{ $user->name }}</td>
              <td>{{ $user->email }}</td>
              <td>{{ $user->phone }}</td>
              <td>{{ number_format($user->salary ?? 0, 2) }}</td>
              <td><span class="table-chip role-{{ $user->role }}">{{ ucfirst($user->role) }}</span></td>
              <td><span class="table-chip status-{{ $user->status ? 'active' : 'inactive' }}">{{ $user->status ? 'Active' : 'Inactive' }}</span></td>
              <td>
                <button class="action-btn folder-user" title="Open folder" type="button"><i class="fa-regular fa-folder"></i></button>
                <button class="action-btn edit-user" title="Edit"><i class="fa-regular fa-pen-to-square"></i></button>
                <button class="action-btn delete-user" title="Delete"><i class="fa-regular fa-trash-can"></i></button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
</section>
@endsection

@section('modals')
<div class="modal fade users-modal" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="userModalTitle">Add User</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="userForm" novalidate>
            <input type="hidden" id="editingRowIndex">
            <div class="mb-2">
              <label class="form-label">Full Name</label>
              <input type="text" class="form-control" id="userName">
              <span class="text-danger error-message" id="userName-error"></span>
            </div>
            <div class="mb-2">
              <label class="form-label">Email</label>
              <input type="text" class="form-control" id="userEmail">
              <span class="text-danger error-message" id="userEmail-error"></span>
            </div>
            <div class="mb-2">
              <label class="form-label">Phone</label>
              <input type="text" class="form-control" id="userPhone">
              <span class="text-danger error-message" id="userPhone-error"></span>
            </div>
            <div class="mb-2">
              <label class="form-label">Salary</label>
              <input type="number" min="0" step="0.01" class="form-control" id="userSalary" placeholder="Enter salary amount">
              <span class="text-danger error-message" id="userSalary-error"></span>
            </div>
            <div class="row g-2">
              <div class="col-sm-6">
                <label class="form-label">Role</label>
                <select class="form-select" id="userRole">
                  <option value="">Select role</option>
                  <option value="foreman">Foreman</option>
                  <option value="driver">Driver</option>
                  <option value="labour">Labour</option>
                </select>
                <span class="text-danger error-message" id="userRole-error"></span>
              </div>
              <div class="col-sm-6">
                <label class="form-label">Status</label>
                <select class="form-select" id="userStatus" required>
                  <option value="">Select status</option>
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
                </select>
                <span class="text-danger error-message" id="userStatus-error"></span>
              </div>
            </div>
            <div class="mt-2">
              <label class="form-label">Password (optional on edit)</label>
              <input type="password" class="form-control" id="userPassword" placeholder="Leave empty to keep password (12345678)">
              <span class="text-danger error-message" id="userPassword-error"></span>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
          <button class="btn btn-save" id="saveUserBtn" type="button">Save User</button>
        </div>
      </div>
    </div>
</div>

<div class="modal fade users-modal" id="deleteUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirm Deletion</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="mb-0">Are you sure you want to delete <strong id="deleteUserName">this user</strong>?</p>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
          <button class="btn btn-danger" id="confirmDeleteUserBtn" type="button">Delete User</button>
        </div>
      </div>
    </div>
</div>

<div class="modal fade users-modal" id="userFolderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fa-regular fa-folder-open me-2"></i><span id="userFolderTitle">User Folder</span></h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="user-folder-path">
            <i class="fa-regular fa-folder"></i>
            <span>user-details</span>
            <span>/</span>
            <strong id="userFolderName">user_id</strong>
            <span>/</span>
            <span>files</span>
          </div>
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <p class="mb-0 small text-secondary">Upload PDFs, images, or other documents for this user.</p>
            <button class="btn btn-upload-docs" id="uploadUserDocsBtn" type="button">
              <i class="fa-solid fa-cloud-arrow-up me-1"></i>Upload Documents
            </button>
            <input type="file" id="userFolderFiles" class="d-none" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,application/pdf">
          </div>
          <div class="user-folder-error" id="userFolderError"></div>
          <div class="user-folder-grid" id="userFolderGrid">
            <div class="user-folder-empty">No documents uploaded yet.</div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">Close</button>
        </div>
      </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
  $(function () {
    const userModal = new bootstrap.Modal(document.getElementById("userModal"));
    const deleteUserModal = new bootstrap.Modal(document.getElementById("deleteUserModal"));
    const userFolderModal = new bootstrap.Modal(document.getElementById("userFolderModal"));
    const table = $("#usersTable").DataTable();
    let pendingDeleteUserId = null;
    let pendingDeleteRowNode = null;
    let pendingFolderUserId = null;

    const roleLabelMap = {
      foreman: "Foreman",
      driver: "Driver",
      labour: "Labour",
    };

    function buildActionButtons() {
      return `
        <button class="action-btn folder-user" title="Open folder" type="button"><i class="fa-regular fa-folder"></i></button>
        <button class="action-btn edit-user" title="Edit"><i class="fa-regular fa-pen-to-square"></i></button>
        <button class="action-btn delete-user" title="Delete"><i class="fa-regular fa-trash-can"></i></button>
      `;
    }

    function buildRoleChip(role) {
      return `<span class="table-chip role-${role}">${roleLabelMap[role] ?? role}</span>`;
    }

    function buildStatusChip(status) {
      return status == 1
        ? '<span class="table-chip status-active">Active</span>'
        : '<span class="table-chip status-inactive">Inactive</span>';
    }

    function resetForm() {
      $("#userForm")[0].reset();
      $(".error-message").html("");
      $("#editingRowIndex").val("");
      $("#userModalTitle").text("Add User");
      $("#userPassword").attr("placeholder", "Leave empty to keep password (12345678)");
    }

    function getPayload() {
      return {
        _token: '{{ csrf_token() }}',
        name: $("#userName").val(),
        email: $("#userEmail").val(),
        phone: $("#userPhone").val(),
        salary: $("#userSalary").val(),
        role: $("#userRole").val(),
        status: $("#userStatus").val(),
        password: $("#userPassword").val(),
      };
    }

    function setErrors(errors = {}) {
      if (errors.name) $("#userName-error").html(`${errors.name[0]}`);
      if (errors.email) $("#userEmail-error").html(`${errors.email[0]}`);
      if (errors.phone) $("#userPhone-error").html(`${errors.phone[0]}`);
      if (errors.salary) $("#userSalary-error").html(`${errors.salary[0]}`);
      if (errors.role) $("#userRole-error").html(`${errors.role[0]}`);
      if (errors.status) $("#userStatus-error").html(`${errors.status[0]}`);
      if (errors.password) $("#userPassword-error").html(`${errors.password[0]}`);
    }

    function cellText(value) {
      if (typeof value !== "string") {
        return value ?? "";
      }

      return $("<div>").html(value).text().trim();
    }

    $("#openAddUser").on("click", function () {
      resetForm();
      userModal.show();
    });

    $("#saveUserBtn").on("click", function () {
      const btn = $(this);
      const editingUserId = $("#editingRowIndex").val();
      const isEdit = !!editingUserId;
      const payload = getPayload();

      btn.prop("disabled", true);
      btn.html('<i class="fa-solid fa-spinner fa-spin"></i> Saving...');
      $(".error-message").html("");

      const request = {
        url: isEdit ? `{{ url('/users') }}/${editingUserId}` : "{{ route('users.store') }}",
        type: isEdit ? "PUT" : "POST",
        data: payload,
      };

      if (isEdit && !payload.password) {
        delete request.data.password;
      }

      $.ajax({
        ...request,
        success: function (response) {
          const user = response.data;

          if (isEdit) {
            const rowNode = $(`#usersTable tbody tr[data-user-id="${user.id}"]`);
            const row = table.row(rowNode);
            row.data([
              user.name,
              user.email,
              user.phone ?? "",
              Number(user.salary ?? 0).toFixed(2),
              buildRoleChip(user.role),
              buildStatusChip(user.status ? 1 : 0),
              buildActionButtons(),
            ]).draw(false);
            $(row.node()).attr("data-user-id", user.id).attr("data-user-name", user.name);
          } else {
            const newRow = table.row.add([
              user.name,
              user.email,
              user.phone ?? "",
              Number(user.salary ?? 0).toFixed(2),
              buildRoleChip(user.role),
              buildStatusChip(user.status ? 1 : 0),
              buildActionButtons(),
            ]).draw(false).node();

            $(newRow).attr("data-user-id", user.id).attr("data-user-name", user.name);
          }

          userModal.hide();
        },
        error: function (xhr) {
          if (xhr.responseJSON?.errors) {
            setErrors(xhr.responseJSON.errors);
          } else if (xhr.responseJSON?.message) {
            $("#userName-error").html(`<i class="fa-solid fa-circle-exclamation"></i> ${xhr.responseJSON.message}`);
          }
        },
        complete: function () {
          btn.prop("disabled", false);
          btn.html("Save User");
        },
      });
    });

    $("#usersTable tbody").on("click", ".delete-user", function () {
      const rowNode = $(this).closest("tr");
      const userId = rowNode.data("user-id");
      const row = table.row(rowNode);
      const data = row.data();
      const userName = cellText(data[0]) || "this user";

      if (!userId) {
        return;
      }

      pendingDeleteUserId = userId;
      pendingDeleteRowNode = rowNode;
      $("#deleteUserName").text(userName);
      deleteUserModal.show();
    });

    $("#confirmDeleteUserBtn").on("click", function () {
      if (!pendingDeleteUserId || !pendingDeleteRowNode) {
        return;
      }

      const btn = $(this);
      btn.prop("disabled", true);
      btn.html('<i class="fa-solid fa-spinner fa-spin"></i> Deleting...');

      $.ajax({
        url: `{{ url('/users') }}/${pendingDeleteUserId}`,
        type: "DELETE",
        data: {
          _token: '{{ csrf_token() }}',
        },
        success: function () {
          table.row(pendingDeleteRowNode).remove().draw(false);
          deleteUserModal.hide();
        },
        error: function (xhr) {
          alert(xhr.responseJSON?.message || "Unable to delete user right now.");
        },
        complete: function () {
          btn.prop("disabled", false);
          btn.html("Delete User");
          pendingDeleteUserId = null;
          pendingDeleteRowNode = null;
        },
      });
    });

    $("#usersTable tbody").on("click", ".edit-user", function () {
      const rowNode = $(this).closest("tr");
      const userId = rowNode.data("user-id");
      const row = table.row(rowNode);
      const data = row.data();

      $("#userName").val(cellText(data[0]));
      $("#userEmail").val(cellText(data[1]));
      $("#userPhone").val(cellText(data[2]));
      $("#userSalary").val(cellText(data[3]).replace(/,/g, ""));
      $("#userRole").val(cellText(data[4]).toLowerCase());
      $("#userStatus").val(cellText(data[5]).toLowerCase() === "active" ? "1" : "0");
      $("#userPassword").val("");
      $("#editingRowIndex").val(userId);
      $("#userModalTitle").text("Edit User");
      $(".error-message").html("");
      userModal.show();
    });

    function escapeHtml(value) {
      return String(value ?? "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;");
    }

    function folderSlug(name, id) {
      const slug = String(name || "user")
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, "-")
        .replace(/^-+|-+$/g, "") || "user";
      return `${slug}_${id}`;
    }

    function filePreviewHtml(doc) {
      if (doc.is_image) {
        return `<a class="user-file-preview" href="${doc.url}" target="_blank" rel="noopener"><img src="${doc.url}" alt="${escapeHtml(doc.name)}"></a>`;
      }
      if (doc.is_pdf) {
        return `<a class="user-file-preview is-pdf" href="${doc.url}" target="_blank" rel="noopener"><i class="fa-regular fa-file-pdf"></i></a>`;
      }
      return `<a class="user-file-preview is-file" href="${doc.url}" target="_blank" rel="noopener"><i class="fa-regular fa-file-lines"></i></a>`;
    }

    function renderFolderDocuments(documents) {
      const $grid = $("#userFolderGrid");
      $grid.empty();

      if (!documents || !documents.length) {
        $grid.html('<div class="user-folder-empty">No documents uploaded yet.</div>');
        return;
      }

      documents.forEach(function (doc) {
        $grid.append(`
          <div class="user-file-card" data-document-id="${doc.id}">
            ${filePreviewHtml(doc)}
            <p class="user-file-name" title="${escapeHtml(doc.name)}">${escapeHtml(doc.name)}</p>
            <button class="user-file-delete" type="button">
              <i class="fa-regular fa-trash-can me-1"></i>Delete
            </button>
          </div>
        `);
      });
    }

    function loadUserFolder(userId) {
      $("#userFolderError").text("");
      $("#userFolderGrid").html('<div class="user-folder-empty">Loading files...</div>');

      $.ajax({
        url: `{{ url('/users') }}/${userId}/documents`,
        type: "GET",
        success: function (response) {
          const folder = response.data?.user?.folder || folderSlug($("#userFolderTitle").text(), userId);
          $("#userFolderName").text(folder);
          renderFolderDocuments(response.data?.documents || []);
        },
        error: function (xhr) {
          $("#userFolderGrid").html('<div class="user-folder-empty">Unable to load documents.</div>');
          $("#userFolderError").text(xhr.responseJSON?.message || "Unable to load documents.");
        },
      });
    }

    $("#usersTable tbody").on("click", ".folder-user", function () {
      const $row = $(this).closest("tr");
      const userId = $row.data("user-id");
      const userName = $row.data("user-name") || cellText(table.row($row).data()?.[0]) || "User";

      if (!userId) return;

      pendingFolderUserId = userId;
      $("#userFolderTitle").text(userName);
      $("#userFolderName").text(folderSlug(userName, userId));
      $("#userFolderError").text("");
      userFolderModal.show();
      loadUserFolder(userId);
    });

    $("#uploadUserDocsBtn").on("click", function () {
      $("#userFolderFiles").trigger("click");
    });

    $("#userFolderFiles").on("change", function () {
      const files = this.files;
      if (!pendingFolderUserId || !files || !files.length) return;

      const formData = new FormData();
      formData.append("_token", "{{ csrf_token() }}");
      Array.from(files).forEach(function (file) {
        formData.append("files[]", file);
      });

      const btn = $("#uploadUserDocsBtn");
      btn.prop("disabled", true).html('<i class="fa-solid fa-spinner fa-spin me-1"></i>Uploading...');
      $("#userFolderError").text("");

      $.ajax({
        url: `{{ url('/users') }}/${pendingFolderUserId}/documents`,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function () {
          loadUserFolder(pendingFolderUserId);
        },
        error: function (xhr) {
          const errors = xhr.responseJSON?.errors;
          const firstError = errors ? Object.values(errors)[0]?.[0] : null;
          $("#userFolderError").text(firstError || xhr.responseJSON?.message || "Unable to upload documents.");
        },
        complete: function () {
          btn.prop("disabled", false).html('<i class="fa-solid fa-cloud-arrow-up me-1"></i>Upload Documents');
          $("#userFolderFiles").val("");
        },
      });
    });

    $("#userFolderGrid").on("click", ".user-file-delete", function () {
      if (!pendingFolderUserId) return;

      const $card = $(this).closest(".user-file-card");
      const documentId = $card.data("document-id");
      if (!documentId || !confirm("Delete this document?")) return;

      const btn = $(this);
      btn.prop("disabled", true).text("Deleting...");

      $.ajax({
        url: `{{ url('/users') }}/${pendingFolderUserId}/documents/${documentId}`,
        type: "DELETE",
        data: { _token: "{{ csrf_token() }}" },
        success: function () {
          loadUserFolder(pendingFolderUserId);
        },
        error: function (xhr) {
          $("#userFolderError").text(xhr.responseJSON?.message || "Unable to delete document.");
          btn.prop("disabled", false).html('<i class="fa-regular fa-trash-can me-1"></i>Delete');
        },
      });
    });

    $("#userModal").on("hidden.bs.modal", function () {
      resetForm();
    });
  });
</script>
@endsection