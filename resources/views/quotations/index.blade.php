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
    text-decoration: none;
    display: inline-flex;
    align-items: center;
  }
  .btn-add-quotation:hover { color: #080059; }
  .action-btn {
    width: 34px; height: 34px; border: 1px solid rgba(8, 0, 89, 0.16); border-radius: 10px;
    background: #fff; color: #080059; transition: all 0.25s ease; margin-right: 0.3rem;
    display: inline-flex; align-items: center; justify-content: center; text-decoration: none;
  }
  .action-btn:hover { color: #080059; }
  .action-btn.delete-quotation:hover { color: #d94b6e; border-color: #d94b6e; }
  .action-btn.view-quotation:hover { color: #2874d9; border-color: #2874d9; }
  .action-btn.edit-quotation:hover { color: #080059; border-color: #eabc73; }
  .action-btn.download-quotation-pdf:hover { color: #1f7a58; border-color: #1f7a58; }
  .quotations-modal .modal-content { border-radius: 16px; border: 1px solid rgba(8, 0, 89, 0.09); overflow: hidden; }
  .quotations-modal .modal-header { border-bottom: 1px solid rgba(8, 0, 89, 0.08); background: linear-gradient(130deg, #080059, #1c109f); color: #fff; }
  .quotations-modal .btn-save { background: linear-gradient(120deg, #eabc73, #f2d39e); color: #080059; border: none; font-weight: 700; }
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
  .items-table-wrap { border: 1px solid rgba(8, 0, 89, 0.1); border-radius: 12px; overflow: hidden; }
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
  .grand-total-box {
    background: linear-gradient(130deg, rgba(8, 0, 89, 0.06), rgba(234, 188, 115, 0.12));
    border-radius: 12px;
    padding: 0.75rem 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .grand-total-box strong { color: #080059; font-size: 1.1rem; }
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
      <a class="btn btn-add-quotation" href="{{ route('dashboard.quotations.create') }}">
        <i class="fa-solid fa-plus me-1"></i>Add Quotation
      </a>
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
              <a class="action-btn edit-quotation" href="{{ route('dashboard.quotations.edit', $quotation) }}" title="Edit"><i class="fa-regular fa-pen-to-square"></i></a>
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
            <label>Contact Person</label>
            <span id="viewContactPerson">—</span>
          </div>
          <div class="details-meta-item">
            <label>Phone</label>
            <span id="viewContactPhone">—</span>
          </div>
          <div class="details-meta-item">
            <label>Date</label>
            <span id="viewQuotationDate">—</span>
          </div>
          <div class="details-meta-item">
            <label>Total Amount</label>
            <span id="viewTotalAmount">—</span>
          </div>
          <div class="details-meta-item">
            <label>Subject</label>
            <span id="viewSubject">—</span>
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
                <th style="width:36%;">Description</th>
                <th style="width:12%;">Unit</th>
                <th style="width:12%;">Qty</th>
                <th style="width:16%;">Unit Price</th>
                <th style="width:16%;">Total</th>
              </tr>
            </thead>
            <tbody id="viewItemsBody"></tbody>
          </table>
        </div>

        <div class="grand-total-box mt-3" id="viewGrandTotalBox">
          <span class="fw-semibold text-secondary">Grand Total</span>
          <strong id="viewGrandTotal">0.00</strong>
        </div>
      </div>
      <div class="modal-footer">
        <a href="#" class="btn btn-save d-none" id="viewPdfBtn" target="_blank" rel="noopener">
          <i class="fa-regular fa-file-pdf me-1"></i>View PDF
        </a>
        <a href="#" class="btn btn-outline-primary d-none" id="viewEditBtn">
          <i class="fa-regular fa-pen-to-square me-1"></i>Edit
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

    function formatCell(value) {
      const text = String(value ?? "").trim();
      if (text === "") return "—";
      if (!isNaN(text) && isFinite(Number(text))) {
        return formatMoney(text);
      }
      return escapeHtml(text);
    }

    function escapeHtml(value) {
      return String(value ?? "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;");
    }

    function populateDetailsModal(quotation, fileUrl) {
      $("#viewQuotationNumber").text(quotation.quotation_number);
      $("#viewCompanyName").text(quotation.company_name);
      $("#viewContactPerson").text(quotation.contact_person || "—");
      $("#viewContactPhone").text(quotation.contact_phone || "—");
      $("#viewQuotationDate").text(String(quotation.quotation_date).slice(0, 10));
      $("#viewTotalAmount").text(formatMoney(quotation.total_amount));
      $("#viewGrandTotal").text(formatMoney(quotation.total_amount));
      if (quotation.show_grand_total === false || quotation.show_grand_total === 0) {
        $("#viewGrandTotalBox").addClass("d-none");
      } else {
        $("#viewGrandTotalBox").removeClass("d-none");
      }
      $("#viewSubject").text(quotation.subject || "—");
      $("#viewEditBtn").attr("href", `{{ url('/dashboard/quotations') }}/${quotation.id}/edit`).removeClass("d-none");

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
        $body.append('<tr><td colspan="6" class="text-center text-muted py-3">No line items found.</td></tr>');
      } else {
        quotation.items.forEach(function (item, index) {
          const isHeading = (item.item_type || "main_item") === "sub_heading";
          const number = item.display_number || (index + 1);
          $body.append(`
            <tr>
              <td>${escapeHtml(number)}</td>
              <td>${escapeHtml(item.description)}</td>
              <td>${isHeading ? "—" : escapeHtml(item.unit || "—")}</td>
              <td>${isHeading ? "—" : formatCell(item.qty)}</td>
              <td>${isHeading ? "—" : formatCell(item.unit_price)}</td>
              <td class="fw-semibold" style="color:#080059;">${isHeading ? "—" : formatCell(item.total)}</td>
            </tr>
          `);
        });
      }

      if (fileUrl) {
        $("#viewPdfBtn").attr("href", fileUrl).removeClass("d-none");
      } else {
        $("#viewPdfBtn").attr("href", "#").addClass("d-none");
      }
    }

    $("#quotationsTable tbody").on("click", ".view-quotation", function () {
      const quotationId = $(this).closest("tr").data("quotation-id");
      if (!quotationId) return;

      $.get(`{{ url('/dashboard/quotations') }}/${quotationId}`, function (response) {
        populateDetailsModal(response.data, response.file_url);
        viewQuotationModal.show();
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
  });
</script>
@endsection
