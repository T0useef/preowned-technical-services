@extends('layouts.dashboard')

@section('title', 'Payments')

@section('style')
<link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<style>
  .payments-content {
    padding: 1.3rem;
  }

  .payments-card {
    border-radius: 16px;
    border: 1px solid rgba(8, 0, 89, 0.08);
    background: rgba(255, 255, 255, 0.72);
    backdrop-filter: blur(10px);
    box-shadow: 0 12px 30px rgba(8, 0, 89, 0.08);
    padding: 1rem;
  }

  .payments-title {
    color: #080059;
    font-weight: 700;
    margin: 0 0 0.3rem;
  }

  .payments-subtitle {
    color: #6f7294;
    margin: 0;
    font-size: 0.9rem;
  }

  .btn-add-advance {
    border: none;
    border-radius: 12px;
    background: linear-gradient(120deg, #eabc73, #f1d19a);
    color: #080059;
    font-weight: 700;
    padding: 0.62rem 0.95rem;
    box-shadow: 0 10px 22px rgba(234, 188, 115, 0.35);
  }

  .payments-modal .modal-content {
    border-radius: 16px;
    border: 1px solid rgba(8, 0, 89, 0.09);
    box-shadow: 0 22px 48px rgba(8, 0, 89, 0.26);
    overflow: hidden;
  }

  .payments-modal .modal-header {
    border-bottom: 1px solid rgba(8, 0, 89, 0.08);
    background: linear-gradient(130deg, #080059, #1c109f);
    color: #fff;
  }

  .payments-modal .modal-title {
    font-size: 1rem;
    font-weight: 700;
  }

  .payments-modal .form-control,
  .payments-modal .form-select {
    border-radius: 10px;
    border: 1px solid rgba(8, 0, 89, 0.16);
    min-height: 42px;
  }

  .payments-modal .form-control:focus,
  .payments-modal .form-select:focus {
    border-color: #eabc73;
    box-shadow: 0 0 0 0.2rem rgba(234, 188, 115, 0.22);
  }

  .payments-modal .btn-save {
    background: linear-gradient(120deg, #eabc73, #f2d39e);
    color: #080059;
    border: none;
    font-weight: 700;
  }

  .payments-error {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.8rem;
    color: #d94b6e;
  }

  .payments-success {
    display: block;
    margin-top: 0.45rem;
    font-size: 0.82rem;
    color: #1f7a58;
    font-weight: 600;
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

  .action-btn.delete-advance:hover {
    color: #d94b6e;
    border-color: #d94b6e;
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
</style>
@endsection

@section('content')
<section class="payments-content">
  <div class="payments-card">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
      <div>
        <h5 class="payments-title">Payments</h5>
        <p class="payments-subtitle">
          Manage advances and payment records from this section.
        </p>
      </div>
      <button class="btn btn-add-advance" id="openAdvanceModal">
        <i class="fa-solid fa-hand-holding-dollar me-1"></i>Add Advance
      </button>
    </div>

    <div class="table-responsive mt-3">
      <table id="advanceTable" class="table align-middle mb-0">
        <thead>
          <tr>
            <th>User</th>
            <th>Amount</th>
            <th>Description</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($advances as $advance)
          <tr data-advance-id="{{ $advance->id }}" data-user-id="{{ $advance->user_id }}">
            <td>{{ $advance->user?->name }}</td>
            <td>{{ number_format($advance->amount, 2) }}</td>
            <td>{{ $advance->description }}</td>
            <td>{{ $advance->created_at?->format('d/m/Y') }}</td>
            <td>
              <button class="action-btn edit-advance" title="Edit"><i class="fa-regular fa-pen-to-square"></i></button>
              <button class="action-btn delete-advance" title="Delete"><i class="fa-regular fa-trash-can"></i></button>
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
<div class="modal fade payments-modal" id="advanceModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Advance</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="advanceForm" novalidate>
          <input type="hidden" id="editingAdvanceId">
          <div class="mb-2">
            <label class="form-label">Select User</label>
            <select class="form-select" id="advanceUserId">
              <option value="">Select user</option>
              @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
              @endforeach
            </select>
            <span class="payments-error" id="advanceUserId-error"></span>
          </div>
          <div class="mb-2">
            <label class="form-label">Amount</label>
            <input type="number" class="form-control" id="advanceAmount" min="0" step="0.01" placeholder="Enter advance amount">
            <span class="payments-error" id="advanceAmount-error"></span>
          </div>
          <div>
            <label class="form-label">Description (optional)</label>
            <textarea class="form-control" id="advanceDescription" rows="3" placeholder="Enter note"></textarea>
            <span class="payments-error" id="advanceDescription-error"></span>
          </div>
          <span class="payments-success" id="advanceSuccessMessage"></span>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">Close</button>
        <button class="btn btn-save" type="button" id="saveAdvanceBtn">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade payments-modal" id="deleteAdvanceModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Deletion</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="mb-0">Are you sure you want to delete advance for <strong id="deleteAdvanceUserName">this user</strong>?</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
        <button class="btn btn-danger" type="button" id="confirmDeleteAdvanceBtn">Delete</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
  $(function () {
    const advanceModal = new bootstrap.Modal(document.getElementById("advanceModal"));
    const deleteAdvanceModal = new bootstrap.Modal(document.getElementById("deleteAdvanceModal"));
    const table = $("#advanceTable").DataTable();
    let pendingDeleteAdvanceId = null;
    let pendingDeleteRowNode = null;

    function buildActionButtons() {
      return `
        <button class="action-btn edit-advance" title="Edit"><i class="fa-regular fa-pen-to-square"></i></button>
        <button class="action-btn delete-advance" title="Delete"><i class="fa-regular fa-trash-can"></i></button>
      `;
    }

    function clearMessages() {
      $("#advanceUserId-error").text("");
      $("#advanceAmount-error").text("");
      $("#advanceDescription-error").text("");
      $("#advanceSuccessMessage").text("");
    }

    function resetAdvanceForm() {
      $("#advanceForm")[0].reset();
      $("#editingAdvanceId").val("");
      $("#saveAdvanceBtn").text("Save");
      $("#advanceModal .modal-title").text("Add Advance");
      clearMessages();
    }

    $("#openAdvanceModal").on("click", function () {
      resetAdvanceForm();
      advanceModal.show();
    });

    $("#advanceModal").on("hidden.bs.modal", function () {
      resetAdvanceForm();
    });

    $("#saveAdvanceBtn").on("click", function () {
      const btn = $(this);
      const advanceId = $("#editingAdvanceId").val();
      const isEdit = !!advanceId;
      clearMessages();

      btn.prop("disabled", true).html('<i class="fa-solid fa-spinner fa-spin"></i> Saving...');

      $.ajax({
        url: isEdit ? `{{ url('/dashboard/payments/advance') }}/${advanceId}` : "{{ route('dashboard.payments.advance.store') }}",
        type: isEdit ? "PUT" : "POST",
        data: {
          _token: "{{ csrf_token() }}",
          user_id: $("#advanceUserId").val(),
          amount: $("#advanceAmount").val(),
          description: $("#advanceDescription").val(),
        },
        success: function (response) {
          $("#advanceSuccessMessage").text(response.message);
          const advance = response.data;
          const rowData = [
            advance.user?.name ?? $("#advanceUserId option:selected").text(),
            Number(advance.amount).toFixed(2),
            advance.description ?? "",
            new Date(advance.created_at).toLocaleDateString("en-GB"),
            buildActionButtons(),
          ];

          if (isEdit) {
            const rowNode = $(`#advanceTable tbody tr[data-advance-id="${advance.id}"]`);
            const row = table.row(rowNode);
            row.data(rowData).draw(false);
            $(row.node()).attr("data-advance-id", advance.id);
            $(row.node()).attr("data-user-id", advance.user_id);
          } else {
            const newRow = table.row.add(rowData).draw(false).node();
            $(newRow).attr("data-advance-id", advance.id);
            $(newRow).attr("data-user-id", advance.user_id);
          }

          advanceModal.hide();
          resetAdvanceForm();
        },
        error: function (xhr) {
          if (xhr.responseJSON?.errors) {
            const errors = xhr.responseJSON.errors;
            if (errors.user_id) $("#advanceUserId-error").text(errors.user_id[0]);
            if (errors.amount) $("#advanceAmount-error").text(errors.amount[0]);
            if (errors.description) $("#advanceDescription-error").text(errors.description[0]);
          } else if (xhr.responseJSON?.message) {
            $("#advanceAmount-error").text(xhr.responseJSON.message);
          }
        },
        complete: function () {
          btn.prop("disabled", false).text(isEdit ? "Update" : "Save");
        }
      });
    });

    $("#advanceTable tbody").on("click", ".edit-advance", function () {
      const rowNode = $(this).closest("tr");
      const advanceId = rowNode.data("advance-id");
      const userId = rowNode.data("user-id");
      const data = table.row(rowNode).data();

      $("#editingAdvanceId").val(advanceId);
      $("#advanceUserId").val(userId || "");
      $("#advanceAmount").val(String(data[1]).replace(/,/g, ""));
      $("#advanceDescription").val(data[2] || "");
      $("#saveAdvanceBtn").text("Update");
      $("#advanceModal .modal-title").text("Edit Advance");
      clearMessages();
      advanceModal.show();
    });

    $("#advanceTable tbody").on("click", ".delete-advance", function () {
      const rowNode = $(this).closest("tr");
      const advanceId = rowNode.data("advance-id");
      const data = table.row(rowNode).data();

      pendingDeleteAdvanceId = advanceId;
      pendingDeleteRowNode = rowNode;
      $("#deleteAdvanceUserName").text(data[0] || "this user");
      deleteAdvanceModal.show();
    });

    $("#confirmDeleteAdvanceBtn").on("click", function () {
      if (!pendingDeleteAdvanceId || !pendingDeleteRowNode) return;

      const btn = $(this);
      btn.prop("disabled", true).html('<i class="fa-solid fa-spinner fa-spin"></i> Deleting...');

      $.ajax({
        url: `{{ url('/dashboard/payments/advance') }}/${pendingDeleteAdvanceId}`,
        type: "DELETE",
        data: {
          _token: "{{ csrf_token() }}",
        },
        success: function () {
          table.row(pendingDeleteRowNode).remove().draw(false);
          deleteAdvanceModal.hide();
        },
        error: function (xhr) {
          alert(xhr.responseJSON?.message || "Unable to delete advance right now.");
        },
        complete: function () {
          btn.prop("disabled", false).text("Delete");
          pendingDeleteAdvanceId = null;
          pendingDeleteRowNode = null;
        }
      });
    });
  });
</script>
@endsection
