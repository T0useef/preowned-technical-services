@extends('layouts.dashboard')

@section('title', 'Quotations')

@section('style')
<link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<style>
  .quotations-content { padding: 1.3rem; }
  .quotations-card {
    border-radius: 16px;
    border: 1px solid rgba(8, 0, 89, 0.08);
    background: rgba(255, 255, 255, 0.72);
    backdrop-filter: blur(10px);
    box-shadow: 0 12px 30px rgba(8, 0, 89, 0.08);
    padding: 1rem;
  }
  .quotations-title { color: #080059; font-weight: 700; margin: 0; }
  .quotations-subtitle { color: #6f7294; margin: 0; font-size: 0.9rem; }
  .btn-add-quotation {
    border: none;
    border-radius: 12px;
    background: linear-gradient(120deg, #eabc73, #f1d19a);
    color: #080059;
    font-weight: 700;
    padding: 0.62rem 0.95rem;
    box-shadow: 0 10px 22px rgba(234, 188, 115, 0.35);
  }
  .action-btn {
    width: 34px; height: 34px; border: 1px solid rgba(8, 0, 89, 0.16); border-radius: 10px;
    background: #fff; color: #080059; transition: all 0.25s ease; margin-right: 0.3rem;
  }
  .action-btn.delete-quotation:hover { color: #d94b6e; border-color: #d94b6e; }
  .action-btn.view-quotation:hover { color: #2874d9; border-color: #2874d9; }
  .action-btn.download-quotation-pdf:hover { color: #1f7a58; border-color: #1f7a58; }
  .quotations-modal .modal-content { border-radius: 16px; border: 1px solid rgba(8, 0, 89, 0.09); overflow: hidden; }
  .quotations-modal .modal-header { border-bottom: 1px solid rgba(8, 0, 89, 0.08); background: linear-gradient(130deg, #080059, #1c109f); color: #fff; }
  .quotations-modal .form-control, .quotations-modal .form-select { border-radius: 10px; border: 1px solid rgba(8, 0, 89, 0.16); min-height: 42px; }
  .quotations-modal .btn-save { background: linear-gradient(120deg, #eabc73, #f2d39e); color: #080059; border: none; font-weight: 700; }
  .error-message { font-size: 0.8rem; display: block; margin-top: 0.2rem; }
  .items-table-wrap { border: 1px solid rgba(8, 0, 89, 0.1); border-radius: 12px; overflow: hidden; }
  .items-table thead th { background: #f4f6ff; color: #080059; font-size: 0.82rem; font-weight: 700; }
  .items-table .form-control { min-height: 38px; font-size: 0.9rem; }
  .line-total { font-weight: 600; color: #080059; white-space: nowrap; }
  .grand-total-box {
    background: linear-gradient(130deg, rgba(8, 0, 89, 0.06), rgba(234, 188, 115, 0.12));
    border-radius: 12px;
    padding: 0.75rem 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .grand-total-box strong { color: #080059; font-size: 1.1rem; }
  .btn-add-line {
    border: 1px dashed rgba(8, 0, 89, 0.3);
    border-radius: 10px;
    background: #fff;
    color: #080059;
    font-weight: 600;
    padding: 0.45rem 0.85rem;
  }
  .btn-remove-line {
    width: 34px; height: 34px; border-radius: 8px;
    border: 1px solid rgba(217, 75, 110, 0.3); background: #fff; color: #d94b6e;
  }
  .quotation-number-chip {
    display: inline-block;
    padding: 0.25rem 0.55rem;
    border-radius: 999px;
    background: rgba(8, 0, 89, 0.08);
    color: #080059;
    font-size: 0.78rem;
    font-weight: 700;
  }
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
  .details-items-table thead th {
    background: #f4f6ff;
    color: #080059;
    font-size: 0.82rem;
    font-weight: 700;
  }
  .details-items-table td {
    font-size: 0.9rem;
    vertical-align: middle;
  }
  .details-notes {
    background: rgba(234, 188, 115, 0.12);
    border: 1px solid rgba(234, 188, 115, 0.35);
    border-radius: 12px;
    padding: 0.75rem 0.85rem;
    margin-bottom: 1rem;
    color: #4a4f72;
    font-size: 0.9rem;
  }
</style>
@endsection

@section('content')
<section class="quotations-content">
  <div class="quotations-card">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
      <div>
        <h5 class="quotations-title">Company Quotations</h5>
        <p class="quotations-subtitle">Create and manage quotations with line items for client companies</p>
      </div>
      <button class="btn btn-add-quotation" id="openAddQuotation">
        <i class="fa-solid fa-plus me-1"></i>Add Quotation
      </button>
    </div>

    <div class="table-responsive">
      <table id="quotationsTable" class="table align-middle mb-0">
        <thead>
          <tr>
            <th>Quotation #</th>
            <th>Company</th>
            <th>Date</th>
            <th>Items</th>
            <th>Total Amount</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($quotations as $quotation)
          <tr data-quotation-id="{{ $quotation->id }}" @if($quotation->file_path) data-file-url="{{ asset($quotation->file_path) }}" @endif>
            <td><span class="quotation-number-chip">{{ $quotation->quotation_number }}</span></td>
            <td>{{ $quotation->company_name }}</td>
            <td>{{ $quotation->quotation_date->format('Y-m-d') }}</td>
            <td>{{ $quotation->items->count() }}</td>
            <td>{{ number_format($quotation->total_amount, 2) }}</td>
            <td>
              <button class="action-btn view-quotation" title="View details"><i class="fa-regular fa-eye"></i></button>
              @if($quotation->file_path)
              <a class="action-btn download-quotation-pdf" href="{{ asset($quotation->file_path) }}" target="_blank" rel="noopener" title="Download PDF"><i class="fa-regular fa-file-pdf"></i></a>
              @endif
              <button class="action-btn edit-quotation" title="Edit"><i class="fa-regular fa-pen-to-square"></i></button>
              <button class="action-btn delete-quotation" title="Delete"><i class="fa-regular fa-trash-can"></i></button>
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
<div class="modal fade quotations-modal" id="quotationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="quotationModalTitle">Add Quotation</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="quotationForm" novalidate>
          <input type="hidden" id="editingQuotationId">
          <div class="row g-2 mb-3">
            <div class="col-md-5">
              <label class="form-label">Company Name</label>
              <input type="text" class="form-control" id="companyName" placeholder="Client company name">
              <span class="text-danger error-message" id="companyName-error"></span>
            </div>
            <div class="col-md-3">
              <label class="form-label">Quotation Date</label>
              <input type="date" class="form-control" id="quotationDate">
              <span class="text-danger error-message" id="quotationDate-error"></span>
            </div>
            <div class="col-md-4">
              <label class="form-label">Notes <span class="text-muted">(optional)</span></label>
              <input type="text" class="form-control" id="quotationNotes" placeholder="Additional notes">
              <span class="text-danger error-message" id="quotationNotes-error"></span>
            </div>
          </div>

          <div class="d-flex align-items-center justify-content-between mb-2">
            <label class="form-label mb-0 fw-semibold" style="color:#080059;">Line Items</label>
            <button type="button" class="btn btn-add-line btn-sm" id="addLineItemBtn">
              <i class="fa-solid fa-plus me-1"></i>Add Line
            </button>
          </div>
          <span class="text-danger error-message d-block mb-2" id="items-error"></span>

          <div class="items-table-wrap mb-3">
            <table class="table items-table mb-0">
              <thead>
                <tr>
                  <th style="width:42%;">Description</th>
                  <th style="width:14%;">Qty</th>
                  <th style="width:18%;">Unit Price</th>
                  <th style="width:18%;">Total</th>
                  <th style="width:8%;"></th>
                </tr>
              </thead>
              <tbody id="lineItemsBody"></tbody>
            </table>
          </div>

          <div class="grand-total-box">
            <span class="fw-semibold text-secondary">Grand Total</span>
            <strong id="grandTotalDisplay">0.00</strong>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
        <button class="btn btn-save" id="saveQuotationBtn" type="button">Save Quotation</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade quotations-modal" id="viewQuotationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Quotation Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="details-meta-grid">
          <div class="details-meta-item">
            <label>Quotation #</label>
            <span id="viewQuotationNumber">—</span>
          </div>
          <div class="details-meta-item">
            <label>Company</label>
            <span id="viewCompanyName">—</span>
          </div>
          <div class="details-meta-item">
            <label>Date</label>
            <span id="viewQuotationDate">—</span>
          </div>
          <div class="details-meta-item">
            <label>Total Amount</label>
            <span id="viewTotalAmount">—</span>
          </div>
        </div>

        <div class="details-notes d-none" id="viewNotesWrap">
          <strong style="color:#080059;">Notes:</strong>
          <span id="viewNotes"></span>
        </div>

        <div class="items-table-wrap">
          <table class="table details-items-table mb-0">
            <thead>
              <tr>
                <th style="width:8%;">#</th>
                <th style="width:44%;">Description</th>
                <th style="width:14%;">Qty</th>
                <th style="width:18%;">Unit Price</th>
                <th style="width:16%;">Total</th>
              </tr>
            </thead>
            <tbody id="viewItemsBody"></tbody>
          </table>
        </div>

        <div class="grand-total-box mt-3">
          <span class="fw-semibold text-secondary">Grand Total</span>
          <strong id="viewGrandTotal">0.00</strong>
        </div>
      </div>
      <div class="modal-footer">
        <a href="#" class="btn btn-save d-none" id="viewPdfBtn" target="_blank" rel="noopener">
          <i class="fa-regular fa-file-pdf me-1"></i>View PDF
        </a>
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade quotations-modal" id="deleteQuotationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Deletion</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="mb-0">Are you sure you want to delete quotation <strong id="deleteQuotationLabel">this quotation</strong>?</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
        <button class="btn btn-danger" id="confirmDeleteQuotationBtn" type="button">Delete Quotation</button>
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
    const quotationModal = new bootstrap.Modal(document.getElementById("quotationModal"));
    const viewQuotationModal = new bootstrap.Modal(document.getElementById("viewQuotationModal"));
    const deleteQuotationModal = new bootstrap.Modal(document.getElementById("deleteQuotationModal"));
    const table = $("#quotationsTable").DataTable({ order: [[2, "desc"]] });
    let pendingDeleteQuotationId = null;
    let pendingDeleteRowNode = null;

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

    function lineItemRow(item = {}) {
      return `
        <tr class="line-item-row">
          <td>
            <input type="text" class="form-control item-description" value="${item.description ?? ""}" placeholder="Item description">
          </td>
          <td>
            <input type="number" min="0.01" step="0.01" class="form-control item-qty" value="${item.qty ?? 1}">
          </td>
          <td>
            <input type="number" min="0" step="0.01" class="form-control item-unit-price" value="${item.unit_price ?? 0}">
          </td>
          <td class="line-total align-middle">0.00</td>
          <td class="align-middle">
            <button type="button" class="btn-remove-line remove-line-item" title="Remove line">
              <i class="fa-solid fa-xmark"></i>
            </button>
          </td>
        </tr>
      `;
    }

    function updateLineTotal($row) {
      const qty = parseFloat($row.find(".item-qty").val()) || 0;
      const unitPrice = parseFloat($row.find(".item-unit-price").val()) || 0;
      const total = qty * unitPrice;
      $row.find(".line-total").text(formatMoney(total));
      updateGrandTotal();
    }

    function updateGrandTotal() {
      let grandTotal = 0;
      $("#lineItemsBody .line-item-row").each(function () {
        const qty = parseFloat($(this).find(".item-qty").val()) || 0;
        const unitPrice = parseFloat($(this).find(".item-unit-price").val()) || 0;
        grandTotal += qty * unitPrice;
      });
      $("#grandTotalDisplay").text(formatMoney(grandTotal));
    }

    function addLineItem(item = {}) {
      $("#lineItemsBody").append(lineItemRow(item));
      const $lastRow = $("#lineItemsBody .line-item-row").last();
      updateLineTotal($lastRow);
    }

    function resetForm() {
      $("#quotationForm")[0].reset();
      $(".error-message").html("");
      $("#editingQuotationId").val("");
      $("#quotationModalTitle").text("Add Quotation");
      $("#lineItemsBody").empty();
      addLineItem();
      $("#quotationDate").val(new Date().toISOString().slice(0, 10));
      updateGrandTotal();
    }

    function collectItems() {
      const items = [];
      $("#lineItemsBody .line-item-row").each(function () {
        items.push({
          description: $(this).find(".item-description").val(),
          qty: $(this).find(".item-qty").val(),
          unit_price: $(this).find(".item-unit-price").val(),
        });
      });
      return items;
    }

    function payload() {
      return {
        _token: "{{ csrf_token() }}",
        company_name: $("#companyName").val(),
        quotation_date: $("#quotationDate").val(),
        notes: $("#quotationNotes").val(),
        items: collectItems(),
      };
    }

    function setErrors(errors = {}) {
      if (errors.company_name) $("#companyName-error").text(errors.company_name[0]);
      if (errors.quotation_date) $("#quotationDate-error").text(errors.quotation_date[0]);
      if (errors.notes) $("#quotationNotes-error").text(errors.notes[0]);
      if (errors.items) $("#items-error").text(Array.isArray(errors.items) ? errors.items[0] : errors.items);

      Object.keys(errors).forEach(function (key) {
        const match = key.match(/^items\.(\d+)\.(\w+)$/);
        if (match) {
          const index = parseInt(match[1], 10);
          const field = match[2];
          const $row = $("#lineItemsBody .line-item-row").eq(index);
          if ($row.length) {
            $row.find(".item-" + field.replace("_", "-")).addClass("is-invalid");
          }
        }
      });
    }

    function buildActionButtons(fileUrl) {
      const pdfBtn = fileUrl
        ? `<a class="action-btn download-quotation-pdf" href="${fileUrl}" target="_blank" rel="noopener" title="Download PDF"><i class="fa-regular fa-file-pdf"></i></a>`
        : "";

      return `
        <button class="action-btn view-quotation" title="View details"><i class="fa-regular fa-eye"></i></button>
        ${pdfBtn}
        <button class="action-btn edit-quotation" title="Edit"><i class="fa-regular fa-pen-to-square"></i></button>
        <button class="action-btn delete-quotation" title="Delete"><i class="fa-regular fa-trash-can"></i></button>
      `;
    }

    function populateDetailsModal(quotation, fileUrl) {
      $("#viewQuotationNumber").text(quotation.quotation_number);
      $("#viewCompanyName").text(quotation.company_name);
      $("#viewQuotationDate").text(String(quotation.quotation_date).slice(0, 10));
      $("#viewTotalAmount").text(formatMoney(quotation.total_amount));
      $("#viewGrandTotal").text(formatMoney(quotation.total_amount));

      if (quotation.notes) {
        $("#viewNotes").text(quotation.notes);
        $("#viewNotesWrap").removeClass("d-none");
      } else {
        $("#viewNotes").text("");
        $("#viewNotesWrap").addClass("d-none");
      }

      const $body = $("#viewItemsBody");
      $body.empty();

      if (!quotation.items || !quotation.items.length) {
        $body.append('<tr><td colspan="5" class="text-center text-muted py-3">No line items found.</td></tr>');
        return;
      }

      quotation.items.forEach(function (item, index) {
        $body.append(`
          <tr>
            <td>${index + 1}</td>
            <td>${escapeHtml(item.description)}</td>
            <td>${formatMoney(item.qty)}</td>
            <td>${formatMoney(item.unit_price)}</td>
            <td class="fw-semibold" style="color:#080059;">${formatMoney(item.total)}</td>
          </tr>
        `);
      });

      if (fileUrl) {
        $("#viewPdfBtn").attr("href", fileUrl).removeClass("d-none");
      } else {
        $("#viewPdfBtn").attr("href", "#").addClass("d-none");
      }
    }

    function buildNumberChip(number) {
      return `<span class="quotation-number-chip">${number}</span>`;
    }

    function populateForm(quotation) {
      $("#companyName").val(quotation.company_name);
      $("#quotationDate").val(String(quotation.quotation_date).slice(0, 10));
      $("#quotationNotes").val(quotation.notes ?? "");
      $("#lineItemsBody").empty();

      if (quotation.items && quotation.items.length) {
        quotation.items.forEach(function (item) {
          addLineItem(item);
        });
      } else {
        addLineItem();
      }

      updateGrandTotal();
    }

    $("#openAddQuotation").on("click", function () {
      resetForm();
      quotationModal.show();
    });

    $("#addLineItemBtn").on("click", function () {
      addLineItem();
    });

    $("#lineItemsBody").on("input", ".item-qty, .item-unit-price", function () {
      updateLineTotal($(this).closest(".line-item-row"));
    });

    $("#lineItemsBody").on("click", ".remove-line-item", function () {
      const rows = $("#lineItemsBody .line-item-row");
      if (rows.length <= 1) {
        $(this).closest(".line-item-row").find("input").val("");
        updateLineTotal($(this).closest(".line-item-row"));
        return;
      }
      $(this).closest(".line-item-row").remove();
      updateGrandTotal();
    });

    $("#saveQuotationBtn").on("click", function () {
      const btn = $(this);
      const quotationId = $("#editingQuotationId").val();
      const isEdit = !!quotationId;

      btn.prop("disabled", true).html('<i class="fa-solid fa-spinner fa-spin"></i> Saving...');
      $(".error-message").html("");
      $("#lineItemsBody .form-control").removeClass("is-invalid");

      $.ajax({
        url: isEdit ? `{{ url('/dashboard/quotations') }}/${quotationId}` : "{{ route('dashboard.quotations.store') }}",
        type: isEdit ? "PUT" : "POST",
        data: payload(),
        success: function (response) {
          const quotation = response.data;
          const fileUrl = response.file_url || null;
          const rowData = [
            buildNumberChip(quotation.quotation_number),
            quotation.company_name,
            quotation.quotation_date,
            quotation.items.length,
            formatMoney(quotation.total_amount),
            buildActionButtons(fileUrl),
          ];

          if (isEdit) {
            const rowNode = $(`#quotationsTable tbody tr[data-quotation-id="${quotation.id}"]`);
            const row = table.row(rowNode);
            row.data(rowData).draw(false);
            $(row.node()).attr("data-quotation-id", quotation.id);
            if (fileUrl) {
              $(row.node()).attr("data-file-url", fileUrl);
            } else {
              $(row.node()).removeAttr("data-file-url");
            }
          } else {
            const newRow = table.row.add(rowData).draw(false).node();
            $(newRow).attr("data-quotation-id", quotation.id);
            if (fileUrl) {
              $(newRow).attr("data-file-url", fileUrl);
            }
          }

          quotationModal.hide();

          if (fileUrl) {
            window.open(fileUrl, "_blank");
          }
        },
        error: function (xhr) {
          if (xhr.responseJSON?.errors) {
            setErrors(xhr.responseJSON.errors);
          }
        },
        complete: function () {
          btn.prop("disabled", false).html("Save Quotation");
        },
      });
    });

    $("#quotationsTable tbody").on("click", ".view-quotation", function () {
      const quotationId = $(this).closest("tr").data("quotation-id");
      if (!quotationId) return;

      $.get(`{{ url('/dashboard/quotations') }}/${quotationId}`, function (response) {
        populateDetailsModal(response.data, response.file_url);
        viewQuotationModal.show();
      });
    });

    $("#quotationsTable tbody").on("click", ".edit-quotation", function () {
      const rowNode = $(this).closest("tr");
      const quotationId = rowNode.data("quotation-id");
      if (!quotationId) return;

      $.get(`{{ url('/dashboard/quotations') }}/${quotationId}`, function (response) {
        const quotation = response.data;
        populateForm(quotation);
        $("#editingQuotationId").val(quotation.id);
        $("#quotationModalTitle").text("Edit Quotation");
        $(".error-message").html("");
        quotationModal.show();
      });
    });

    $("#quotationsTable tbody").on("click", ".delete-quotation", function () {
      const rowNode = $(this).closest("tr");
      const quotationId = rowNode.data("quotation-id");
      const row = table.row(rowNode);
      const data = row.data();

      if (!quotationId) return;

      pendingDeleteQuotationId = quotationId;
      pendingDeleteRowNode = rowNode;
      $("#deleteQuotationLabel").text(cellText(data[0]) + " — " + cellText(data[1]));
      deleteQuotationModal.show();
    });

    $("#confirmDeleteQuotationBtn").on("click", function () {
      if (!pendingDeleteQuotationId || !pendingDeleteRowNode) return;

      const btn = $(this);
      btn.prop("disabled", true).html('<i class="fa-solid fa-spinner fa-spin"></i> Deleting...');

      $.ajax({
        url: `{{ url('/dashboard/quotations') }}/${pendingDeleteQuotationId}`,
        type: "DELETE",
        data: { _token: "{{ csrf_token() }}" },
        success: function () {
          table.row(pendingDeleteRowNode).remove().draw(false);
          deleteQuotationModal.hide();
        },
        complete: function () {
          btn.prop("disabled", false).html("Delete Quotation");
          pendingDeleteQuotationId = null;
          pendingDeleteRowNode = null;
        },
      });
    });

    $("#quotationModal").on("hidden.bs.modal", function () {
      resetForm();
    });
  });
</script>
@endsection
