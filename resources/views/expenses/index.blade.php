@extends('layouts.dashboard')

@section('title', 'Expenses')

@section('style')
<link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<style>
  .expenses-content { padding: 1.3rem; }

  .expenses-card {
    border-radius: 16px;
    border: 1px solid rgba(8, 0, 89, 0.08);
    background: rgba(255, 255, 255, 0.72);
    backdrop-filter: blur(10px);
    box-shadow: 0 12px 30px rgba(8, 0, 89, 0.08);
    padding: 1rem;
  }

  .expenses-title { color: #080059; font-weight: 700; margin: 0; }
  .expenses-subtitle { color: #6f7294; margin: 0; font-size: 0.9rem; }

  .btn-add-expense {
    border: none;
    border-radius: 12px;
    background: linear-gradient(120deg, #eabc73, #f1d19a);
    color: #080059;
    font-weight: 700;
    padding: 0.62rem 0.95rem;
    box-shadow: 0 10px 22px rgba(234, 188, 115, 0.35);
  }

  .btn-add-expense:hover { color: #080059; transform: translateY(-2px); }

  .receipt-summary {
    display: inline-flex;
    align-items: center;
    gap: 0.55rem;
    border: 1px solid rgba(8, 0, 89, 0.12);
    border-radius: 10px;
    background: #fff;
    padding: 0.38rem 0.7rem;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .receipt-summary:hover {
    border-color: #eabc73;
    background: rgba(234, 188, 115, 0.08);
  }

  .receipt-count-item {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.84rem;
    font-weight: 700;
    line-height: 1;
  }

  .receipt-count-item.is-image { color: #2874d9; }
  .receipt-count-item.is-pdf { color: #b13a50; }

  .receipt-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 0.75rem;
    margin-top: 0.85rem;
  }

  .receipt-preview-card {
    position: relative;
    border: 1px solid rgba(8, 0, 89, 0.1);
    border-radius: 12px;
    background: #fff;
    overflow: hidden;
    padding: 0.55rem;
  }

  .receipt-preview-card .receipt-remove-btn {
    position: absolute;
    top: 0.45rem;
    right: 0.45rem;
    z-index: 2;
  }

  .receipt-preview-image {
    width: 100%;
    height: 110px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid rgba(8, 0, 89, 0.08);
    background: #f8f9ff;
    display: block;
  }

  .receipt-preview-pdf {
    height: 110px;
    border-radius: 10px;
    border: 1px dashed rgba(8, 0, 89, 0.16);
    background: rgba(220, 76, 100, 0.06);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #b13a50;
    font-size: 2rem;
  }

  .receipt-list-item-name {
    display: block;
    font-size: 0.78rem;
    font-weight: 600;
    color: #080059;
    margin-top: 0.55rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .receipt-remove-btn {
    width: 28px;
    height: 28px;
    border: none;
    border-radius: 8px;
    background: rgba(220, 76, 100, 0.12);
    color: #b13a50;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .receipt-remove-btn:hover {
    background: rgba(220, 76, 100, 0.2);
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
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .action-btn:hover { border-color: #eabc73; color: #080059; }
  .action-btn.view-expense:hover { color: #2874d9; border-color: #2874d9; }
  .action-btn.delete-expense:hover { color: #d94b6e; border-color: #d94b6e; }

  .details-meta-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 0.75rem;
    margin-bottom: 1rem;
  }

  .details-meta-item {
    background: rgba(8, 0, 89, 0.04);
    border: 1px solid rgba(8, 0, 89, 0.08);
    border-radius: 12px;
    padding: 0.75rem 0.85rem;
  }

  .details-meta-item label {
    display: block;
    font-size: 0.75rem;
    color: #6f7294;
    margin-bottom: 0.2rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.4px;
  }

  .details-meta-item span {
    color: #080059;
    font-weight: 600;
    font-size: 0.95rem;
  }

  .details-description {
    background: rgba(234, 188, 115, 0.12);
    border: 1px solid rgba(234, 188, 115, 0.35);
    border-radius: 12px;
    padding: 0.75rem 0.85rem;
    margin-bottom: 1rem;
    color: #4a4f72;
    font-size: 0.9rem;
  }

  .view-receipts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 0.75rem;
  }

  .view-receipt-card {
    border: 1px solid rgba(8, 0, 89, 0.1);
    border-radius: 12px;
    background: #fff;
    padding: 0.55rem;
    text-decoration: none;
    color: inherit;
    display: block;
  }

  .view-receipt-card:hover {
    border-color: #eabc73;
    color: inherit;
  }

  .view-receipt-card .receipt-preview-image,
  .view-receipt-card .receipt-preview-pdf {
    pointer-events: none;
  }

  .expenses-modal .modal-content {
    border-radius: 16px;
    border: 1px solid rgba(8, 0, 89, 0.09);
    overflow: hidden;
  }

  .expenses-modal .modal-header {
    border-bottom: 1px solid rgba(8, 0, 89, 0.08);
    background: linear-gradient(130deg, #080059, #1c109f);
    color: #fff;
  }

  .expenses-modal .form-control,
  .expenses-modal .form-select {
    border-radius: 10px;
    border: 1px solid rgba(8, 0, 89, 0.16);
    min-height: 42px;
  }

  .expenses-modal .form-control:focus {
    border-color: #eabc73;
    box-shadow: 0 0 0 0.2rem rgba(234, 188, 115, 0.22);
  }

  .expenses-modal .btn-save {
    background: linear-gradient(120deg, #eabc73, #f2d39e);
    color: #080059;
    border: none;
    font-weight: 700;
  }

  .error-message {
    font-size: 0.8rem;
    display: block;
    margin-top: 0.2rem;
  }

  .receipt-dropzone {
    position: relative;
    border: 2px dashed rgba(8, 0, 89, 0.18);
    border-radius: 14px;
    padding: 1.35rem 1rem;
    background: linear-gradient(180deg, rgba(8, 0, 89, 0.03), rgba(234, 188, 115, 0.06));
    text-align: center;
    cursor: pointer;
    transition: all 0.25s ease;
  }

  .receipt-dropzone:hover,
  .receipt-dropzone.is-dragover {
    border-color: #eabc73;
    background: rgba(234, 188, 115, 0.12);
    transform: translateY(-1px);
  }

  .receipt-dropzone.has-file {
    border-color: rgba(234, 188, 115, 0.55);
    background: rgba(234, 188, 115, 0.08);
  }

  .receipt-dropzone input[type="file"] {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
  }

  .receipt-dropzone-icon {
    width: 52px;
    height: 52px;
    margin: 0 auto 0.75rem;
    border-radius: 14px;
    background: rgba(8, 0, 89, 0.08);
    color: #080059;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
  }

  .receipt-dropzone-title {
    color: #080059;
    font-weight: 700;
    margin-bottom: 0.25rem;
  }

  .receipt-dropzone-text {
    font-size: 0.85rem;
    color: #6f7294;
    margin-bottom: 0;
  }

  .receipt-list-empty {
    font-size: 0.82rem;
    color: #6f7294;
    margin-top: 0.75rem;
  }

  .receipt-list-empty.d-none { display: none !important; }

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
</style>
@endsection

@section('content')
<section class="expenses-content">
  <div class="expenses-card">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
      <div>
        <h5 class="expenses-title">Expense Management</h5>
        <p class="expenses-subtitle">Track company expenses with receipts and descriptions</p>
      </div>
      <button class="btn btn-add-expense" id="openAddExpense" type="button">
        <i class="fa-solid fa-plus me-1"></i>Add Expense
      </button>
    </div>

    <div class="table-responsive">
      <table id="expensesTable" class="table align-middle mb-0">
        <thead>
          <tr>
            <th>Date</th>
            <th>Description</th>
            <th>Price</th>
            <th>Receipt</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($expenses as $expense)
          @php
            $expenseReceiptsPayload = $expense->receipts->map(function ($receipt) {
              return [
                'id' => $receipt->id,
                'name' => $receipt->original_name,
                'url' => asset($receipt->file_path),
                'is_pdf' => str_ends_with(strtolower($receipt->original_name ?: $receipt->file_path), '.pdf'),
              ];
            })->values();
            $imageCount = $expenseReceiptsPayload->where('is_pdf', false)->count();
            $pdfCount = $expenseReceiptsPayload->where('is_pdf', true)->count();
          @endphp
          <tr
            data-expense-id="{{ $expense->id }}"
            data-expense-date="{{ $expense->expense_date->format('Y-m-d') }}"
            data-expense-description="{{ e($expense->description) }}"
            data-expense-price="{{ number_format((float) $expense->price, 2, '.', '') }}"
            data-receipts='@json($expenseReceiptsPayload)'
          >
            <td>{{ $expense->expense_date->format('Y-m-d') }}</td>
            <td>{{ $expense->description }}</td>
            <td>{{ number_format((float) $expense->price, 2) }}</td>
            <td>
              @if($expense->receipts->isNotEmpty())
                <button class="receipt-summary view-expense" title="View details" type="button">
                  @if($imageCount > 0)
                    <span class="receipt-count-item is-image">
                      <i class="fa-regular fa-image"></i>
                      <span>{{ $imageCount }}</span>
                    </span>
                  @endif
                  @if($pdfCount > 0)
                    <span class="receipt-count-item is-pdf">
                      <i class="fa-regular fa-file-pdf"></i>
                      <span>{{ $pdfCount }}</span>
                    </span>
                  @endif
                </button>
              @else
                <span class="text-muted">—</span>
              @endif
            </td>
            <td>
              <button class="action-btn view-expense" title="View details" type="button"><i class="fa-regular fa-eye"></i></button>
              <button class="action-btn edit-expense" title="Edit" type="button"><i class="fa-regular fa-pen-to-square"></i></button>
              <button class="action-btn delete-expense" title="Delete" type="button"><i class="fa-regular fa-trash-can"></i></button>
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
<div class="modal fade expenses-modal" id="expenseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="expenseModalTitle">Add Expense</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="expenseForm" novalidate>
          <input type="hidden" id="editingExpenseId" value="">
          <div class="mb-3">
            <label class="form-label" for="expenseDate">Date</label>
            <input type="date" class="form-control" id="expenseDate">
            <span class="text-danger error-message" id="expenseDate-error"></span>
          </div>
          <div class="mb-3">
            <label class="form-label" for="expensePrice">Price</label>
            <input type="number" min="0" step="0.01" class="form-control" id="expensePrice" placeholder="Enter amount">
            <span class="text-danger error-message" id="expensePrice-error"></span>
          </div>
          <div class="mb-3">
            <label class="form-label" for="expenseDescription">Description</label>
            <textarea class="form-control" id="expenseDescription" rows="3" placeholder="Enter expense description"></textarea>
            <span class="text-danger error-message" id="expenseDescription-error"></span>
          </div>
          <div class="mb-0">
            <label class="form-label">Receipts</label>
            <div class="receipt-dropzone" id="receiptDropzone">
              <input type="file" id="expenseReceipt" accept="image/*,.pdf,application/pdf" multiple>
              <div class="receipt-dropzone-icon">
                <i class="fa-solid fa-cloud-arrow-up"></i>
              </div>
              <div class="receipt-dropzone-title">Drop receipts here</div>
              <p class="receipt-dropzone-text" id="receiptDropzoneHint">or click to upload images / PDFs</p>
            </div>
            <div class="receipt-list d-none" id="receiptSelectedList"></div>
            <div class="receipt-list-empty" id="receiptListEmpty">No receipts selected yet.</div>
            <span class="text-danger error-message" id="expenseReceipt-error"></span>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
        <button class="btn btn-save" id="saveExpenseBtn" type="button">Save Expense</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade expenses-modal" id="deleteExpenseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Deletion</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="mb-0">Are you sure you want to delete expense <strong id="deleteExpenseLabel">this expense</strong>?</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
        <button class="btn btn-danger" id="confirmDeleteExpenseBtn" type="button">Delete Expense</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade expenses-modal" id="viewExpenseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Expense Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="details-meta-grid">
          <div class="details-meta-item">
            <label>Date</label>
            <span id="viewExpenseDate">—</span>
          </div>
          <div class="details-meta-item">
            <label>Price</label>
            <span id="viewExpensePrice">—</span>
          </div>
        </div>

        <div class="details-description">
          <strong style="color:#080059;">Description</strong>
          <div id="viewExpenseDescription" class="mt-1">—</div>
        </div>

        <label class="form-label mb-2">Receipts</label>
        <div class="view-receipts-grid" id="viewExpenseReceipts">
          <span class="text-muted">No receipts found.</span>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-primary" id="viewExpenseEditBtn" type="button">
          <i class="fa-regular fa-pen-to-square me-1"></i>Edit
        </button>
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">Close</button>
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
    const expenseModal = new bootstrap.Modal(document.getElementById("expenseModal"));
    const viewExpenseModal = new bootstrap.Modal(document.getElementById("viewExpenseModal"));
    const deleteExpenseModal = new bootstrap.Modal(document.getElementById("deleteExpenseModal"));
    const table = $("#expensesTable").DataTable({ order: [[0, "desc"]] });
    let pendingDeleteExpenseId = null;
    let pendingDeleteRowNode = null;
    let pendingViewExpenseId = null;
    let pendingFiles = [];
    let existingReceipts = [];
    let removedReceiptIds = [];

    function cellText(value) {
      if (typeof value !== "string") return value ?? "";
      return $("<div>").html(value).text().trim();
    }

    function formatMoney(value) {
      return Number(value || 0).toFixed(2);
    }

    function escapeHtml(value) {
      return String(value ?? "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;");
    }

    function makePendingId() {
      return "pending-" + Date.now() + "-" + Math.random().toString(36).slice(2, 8);
    }

    function buildReceiptSummaryHtml(imageCount, pdfCount) {
      const parts = [];

      if (imageCount > 0) {
        parts.push(`<span class="receipt-count-item is-image"><i class="fa-regular fa-image"></i><span>${imageCount}</span></span>`);
      }

      if (pdfCount > 0) {
        parts.push(`<span class="receipt-count-item is-pdf"><i class="fa-regular fa-file-pdf"></i><span>${pdfCount}</span></span>`);
      }

      return `<button class="receipt-summary view-expense" title="View details" type="button">${parts.join("")}</button>`;
    }

    function buildReceiptCell(expense) {
      if (!expense.receipts || !expense.receipts.length) {
        return '<span class="text-muted">—</span>';
      }

      let imageCount = 0;
      let pdfCount = 0;

      expense.receipts.forEach(function (receipt) {
        if (receipt.is_pdf) {
          pdfCount += 1;
        } else {
          imageCount += 1;
        }
      });

      return buildReceiptSummaryHtml(imageCount, pdfCount);
    }

    function buildActionButtons() {
      return `
        <button class="action-btn view-expense" title="View details" type="button"><i class="fa-regular fa-eye"></i></button>
        <button class="action-btn edit-expense" title="Edit" type="button"><i class="fa-regular fa-pen-to-square"></i></button>
        <button class="action-btn delete-expense" title="Delete" type="button"><i class="fa-regular fa-trash-can"></i></button>
      `;
    }

    function buildViewReceiptHtml(receipt) {
      if (receipt.is_pdf) {
        return `
          <a class="view-receipt-card is-pdf" href="${receipt.url}" target="_blank" rel="noopener">
            <div class="receipt-preview-pdf">
              <i class="fa-regular fa-file-pdf"></i>
            </div>
            <span class="receipt-list-item-name" title="${escapeHtml(receipt.name)}">${escapeHtml(receipt.name)}</span>
          </a>
        `;
      }

      return `
        <a class="view-receipt-card" href="${receipt.url}" target="_blank" rel="noopener">
          <img class="receipt-preview-image" src="${receipt.url}" alt="${escapeHtml(receipt.name)}">
          <span class="receipt-list-item-name" title="${escapeHtml(receipt.name)}">${escapeHtml(receipt.name)}</span>
        </a>
      `;
    }

    function populateViewModal($row) {
      pendingViewExpenseId = $row.data("expense-id");
      $("#viewExpenseDate").text($row.data("expense-date") || "—");
      $("#viewExpensePrice").text(formatMoney($row.data("expense-price")));
      $("#viewExpenseDescription").text($row.data("expense-description") || "—");

      let receipts = [];
      try {
        receipts = JSON.parse($row.attr("data-receipts") || "[]");
      } catch (e) {
        receipts = [];
      }

      const $container = $("#viewExpenseReceipts");
      $container.empty();

      if (!receipts.length) {
        $container.html('<span class="text-muted">No receipts found.</span>');
      } else {
        receipts.forEach(function (receipt) {
          $container.append(buildViewReceiptHtml(receipt));
        });
      }
    }

    function openEditFromRow($row) {
      resetForm();
      $("#expenseModalTitle").text("Edit Expense");
      $("#saveExpenseBtn").text("Update Expense");
      $("#editingExpenseId").val($row.data("expense-id"));
      $("#expenseDate").val($row.data("expense-date"));
      $("#expensePrice").val($row.data("expense-price"));
      $("#expenseDescription").val($row.data("expense-description"));

      let receipts = [];
      try {
        receipts = JSON.parse($row.attr("data-receipts") || "[]");
      } catch (e) {
        receipts = [];
      }

      existingReceipts = receipts;
      renderReceiptList();
      expenseModal.show();
    }

    function setRowData($row, expense) {
      $row.attr({
        "data-expense-id": expense.id,
        "data-expense-date": expense.expense_date,
        "data-expense-description": expense.description,
        "data-expense-price": expense.price,
        "data-receipts": JSON.stringify(expense.receipts || []),
      });
    }

    function syncFileInput() {
      const input = document.getElementById("expenseReceipt");
      const dataTransfer = new DataTransfer();
      pendingFiles.forEach(function (item) {
        dataTransfer.items.add(item.file);
      });
      input.files = dataTransfer.files;
    }

    function isPdfFile(file) {
      return file.type === "application/pdf" || /\.pdf$/i.test(file.name || "");
    }

    function isImageFile(file) {
      return (file.type && file.type.startsWith("image/")) || /\.(jpe?g|png|webp|gif)$/i.test(file.name || "");
    }

    function revokePreviewUrl(url) {
      if (url) {
        URL.revokeObjectURL(url);
      }
    }

    function buildExistingReceiptHtml(receipt) {
      if (receipt.is_pdf) {
        return `
          <div class="receipt-preview-card is-pdf" data-existing-receipt-id="${receipt.id}">
            <button type="button" class="receipt-remove-btn remove-existing-receipt" title="Remove receipt">
              <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="receipt-preview-pdf">
              <i class="fa-regular fa-file-pdf"></i>
            </div>
            <span class="receipt-list-item-name" title="${escapeHtml(receipt.name)}">${escapeHtml(receipt.name)}</span>
          </div>
        `;
      }

      return `
        <div class="receipt-preview-card" data-existing-receipt-id="${receipt.id}">
          <button type="button" class="receipt-remove-btn remove-existing-receipt" title="Remove receipt">
            <i class="fa-solid fa-xmark"></i>
          </button>
          <img class="receipt-preview-image" src="${receipt.url}" alt="${escapeHtml(receipt.name)}">
          <span class="receipt-list-item-name" title="${escapeHtml(receipt.name)}">${escapeHtml(receipt.name)}</span>
        </div>
      `;
    }

    function buildPendingReceiptHtml(item) {
      if (isPdfFile(item.file)) {
        return `
          <div class="receipt-preview-card is-pdf" data-pending-id="${item.id}">
            <button type="button" class="receipt-remove-btn remove-pending-receipt" title="Remove receipt">
              <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="receipt-preview-pdf">
              <i class="fa-regular fa-file-pdf"></i>
            </div>
            <span class="receipt-list-item-name" title="${escapeHtml(item.file.name)}">${escapeHtml(item.file.name)}</span>
          </div>
        `;
      }

      const previewUrl = item.previewUrl || "";
      return `
        <div class="receipt-preview-card" data-pending-id="${item.id}">
          <button type="button" class="receipt-remove-btn remove-pending-receipt" title="Remove receipt">
            <i class="fa-solid fa-xmark"></i>
          </button>
          <img class="receipt-preview-image" src="${previewUrl}" alt="${escapeHtml(item.file.name)}">
          <span class="receipt-list-item-name" title="${escapeHtml(item.file.name)}">${escapeHtml(item.file.name)}</span>
        </div>
      `;
    }

    function renderReceiptList() {
      const $list = $("#receiptSelectedList");
      $list.empty();

      existingReceipts.forEach(function (receipt) {
        if (removedReceiptIds.includes(receipt.id)) {
          return;
        }
        $list.append(buildExistingReceiptHtml(receipt));
      });

      pendingFiles.forEach(function (item) {
        $list.append(buildPendingReceiptHtml(item));
      });

      const totalVisible = existingReceipts.filter(function (receipt) {
        return !removedReceiptIds.includes(receipt.id);
      }).length + pendingFiles.length;

      $("#receiptDropzone").toggleClass("has-file", totalVisible > 0);
      $("#receiptSelectedList").toggleClass("d-none", totalVisible === 0);
      $("#receiptListEmpty").toggleClass("d-none", totalVisible > 0);
    }

    function addFiles(fileList) {
      Array.from(fileList || []).forEach(function (file) {
        const item = { id: makePendingId(), file: file, previewUrl: null };
        if (isImageFile(file)) {
          item.previewUrl = URL.createObjectURL(file);
        }
        pendingFiles.push(item);
      });
      syncFileInput();
      renderReceiptList();
      $("#expenseReceipt").val("");
    }

    function resetReceiptState() {
      pendingFiles.forEach(function (item) {
        revokePreviewUrl(item.previewUrl);
      });
      pendingFiles = [];
      existingReceipts = [];
      removedReceiptIds = [];
      $("#expenseReceipt").val("");
      $("#receiptDropzone").removeClass("has-file is-dragover");
      renderReceiptList();
    }

    function resetForm() {
      $("#expenseForm")[0].reset();
      $(".error-message").html("");
      $("#editingExpenseId").val("");
      $("#expenseModalTitle").text("Add Expense");
      $("#saveExpenseBtn").text("Save Expense");
      resetReceiptState();
    }

    function setErrors(errors = {}) {
      $(".error-message").html("");
      if (errors.expense_date) $("#expenseDate-error").text(errors.expense_date[0]);
      if (errors.price) $("#expensePrice-error").text(errors.price[0]);
      if (errors.description) $("#expenseDescription-error").text(errors.description[0]);
      if (errors.receipts) $("#expenseReceipt-error").text(Array.isArray(errors.receipts) ? errors.receipts[0] : errors.receipts);
    }

    function buildFormData() {
      const formData = new FormData();
      formData.append("_token", "{{ csrf_token() }}");
      formData.append("expense_date", $("#expenseDate").val());
      formData.append("price", $("#expensePrice").val());
      formData.append("description", $("#expenseDescription").val());

      pendingFiles.forEach(function (item, index) {
        formData.append(`receipts[${index}]`, item.file);
      });

      removedReceiptIds.forEach(function (id) {
        formData.append("removed_receipt_ids[]", id);
      });

      return formData;
    }

    $("#openAddExpense").on("click", function () {
      resetForm();
      $("#expenseDate").val(new Date().toISOString().slice(0, 10));
      expenseModal.show();
    });

    $("#expensesTable tbody").on("click", ".view-expense", function () {
      const $row = $(this).closest("tr");
      populateViewModal($row);
      viewExpenseModal.show();
    });

    $("#viewExpenseEditBtn").on("click", function () {
      if (!pendingViewExpenseId) return;
      const $row = $(`#expensesTable tbody tr[data-expense-id="${pendingViewExpenseId}"]`);
      if (!$row.length) return;
      viewExpenseModal.hide();
      openEditFromRow($row);
    });

    $("#expensesTable tbody").on("click", ".edit-expense", function () {
      const $row = $(this).closest("tr");
      openEditFromRow($row);
    });

    $("#expensesTable tbody").on("click", ".delete-expense", function () {
      const rowNode = $(this).closest("tr");
      const expenseId = rowNode.data("expense-id");
      const row = table.row(rowNode);
      const data = row.data();

      if (!expenseId) return;

      pendingDeleteExpenseId = expenseId;
      pendingDeleteRowNode = rowNode;
      $("#deleteExpenseLabel").text(cellText(data[1]) || "this expense");
      deleteExpenseModal.show();
    });

    $("#receiptSelectedList").on("click", ".remove-pending-receipt", function () {
      const pendingId = $(this).closest(".receipt-preview-card").data("pending-id");
      pendingFiles = pendingFiles.filter(function (item) {
        if (item.id === pendingId) {
          revokePreviewUrl(item.previewUrl);
          return false;
        }
        return true;
      });
      syncFileInput();
      renderReceiptList();
    });

    $("#receiptSelectedList").on("click", ".remove-existing-receipt", function () {
      const receiptId = Number($(this).closest(".receipt-preview-card").data("existing-receipt-id"));
      if (!removedReceiptIds.includes(receiptId)) {
        removedReceiptIds.push(receiptId);
      }
      renderReceiptList();
    });

    $("#saveExpenseBtn").on("click", function () {
      const btn = $(this);
      const expenseId = $("#editingExpenseId").val();
      const isEdit = !!expenseId;
      const formData = buildFormData();

      if (isEdit) {
        formData.append("_method", "PUT");
      }

      btn.prop("disabled", true).html('<i class="fa-solid fa-spinner fa-spin"></i> Saving...');
      $(".error-message").html("");

      $.ajax({
        url: isEdit ? `{{ url('/dashboard/expenses') }}/${expenseId}` : "{{ route('dashboard.expenses.store') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          const expense = response.data;

          if (isEdit) {
            const rowNode = $(`#expensesTable tbody tr[data-expense-id="${expense.id}"]`);
            const row = table.row(rowNode);
            row.data([
              expense.expense_date,
              expense.description,
              formatMoney(expense.price),
              buildReceiptCell(expense),
              buildActionButtons(),
            ]).draw(false);
            setRowData($(row.node()), expense);
          } else {
            const newRow = table.row.add([
              expense.expense_date,
              expense.description,
              formatMoney(expense.price),
              buildReceiptCell(expense),
              buildActionButtons(),
            ]).draw(false).node();
            setRowData($(newRow), expense);
          }

          expenseModal.hide();
        },
        error: function (xhr) {
          if (xhr.responseJSON?.errors) {
            setErrors(xhr.responseJSON.errors);
          } else if (xhr.responseJSON?.message) {
            $("#expenseDescription-error").text(xhr.responseJSON.message);
          }
        },
        complete: function () {
          btn.prop("disabled", false).text(isEdit ? "Update Expense" : "Save Expense");
        },
      });
    });

    $("#confirmDeleteExpenseBtn").on("click", function () {
      if (!pendingDeleteExpenseId || !pendingDeleteRowNode) return;

      const btn = $(this);
      btn.prop("disabled", true).html('<i class="fa-solid fa-spinner fa-spin"></i> Deleting...');

      $.ajax({
        url: `{{ url('/dashboard/expenses') }}/${pendingDeleteExpenseId}`,
        type: "DELETE",
        data: { _token: "{{ csrf_token() }}" },
        success: function () {
          table.row(pendingDeleteRowNode).remove().draw(false);
          deleteExpenseModal.hide();
        },
        complete: function () {
          btn.prop("disabled", false).html("Delete Expense");
          pendingDeleteExpenseId = null;
          pendingDeleteRowNode = null;
        },
      });
    });

    $("#expenseReceipt").on("change", function () {
      if (this.files && this.files.length) {
        addFiles(this.files);
      }
    });

    $("#receiptDropzone").on("dragover dragenter", function (e) {
      e.preventDefault();
      e.stopPropagation();
      $(this).addClass("is-dragover");
    });

    $("#receiptDropzone").on("dragleave dragend drop", function (e) {
      e.preventDefault();
      e.stopPropagation();
      $(this).removeClass("is-dragover");
    });

    $("#receiptDropzone").on("drop", function (e) {
      const files = e.originalEvent.dataTransfer && e.originalEvent.dataTransfer.files;
      if (!files || !files.length) return;
      addFiles(files);
    });

    $("#expenseModal").on("hidden.bs.modal", resetForm);
  });
</script>
@endsection
