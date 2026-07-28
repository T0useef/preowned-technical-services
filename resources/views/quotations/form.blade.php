@extends('layouts.dashboard')

@section('title', $pageTitle)

@section('style')
<style>
  .quotations-content { padding: 1.3rem; }
  .quotations-card {
    border-radius: 16px;
    border: 1px solid rgba(8, 0, 89, 0.08);
    background: rgba(255, 255, 255, 0.72);
    backdrop-filter: blur(10px);
    box-shadow: 0 12px 30px rgba(8, 0, 89, 0.08);
    padding: 1.25rem;
  }
  .quotations-title { color: #080059; font-weight: 700; margin: 0; }
  .quotations-subtitle { color: #6f7294; margin: 0; font-size: 0.9rem; }
  .btn-back {
    border: 1px solid rgba(8, 0, 89, 0.2);
    background: #fff;
    color: #080059;
    font-weight: 600;
    border-radius: 10px;
  }
  .btn-save {
    background: linear-gradient(120deg, #eabc73, #f2d39e);
    color: #080059;
    border: none;
    font-weight: 700;
    border-radius: 10px;
    padding: 0.55rem 1rem;
  }
  .btn-preview {
    border: 1px solid rgba(8, 0, 89, 0.25);
    background: #fff;
    color: #080059;
    font-weight: 700;
    border-radius: 10px;
    padding: 0.55rem 1rem;
  }
  .btn-preview:hover {
    background: rgba(8, 0, 89, 0.06);
    color: #080059;
  }
  .form-control, .form-select {
    border-radius: 10px;
    border: 1px solid rgba(8, 0, 89, 0.16);
    min-height: 42px;
  }
  .error-message { font-size: 0.8rem; display: block; margin-top: 0.2rem; }
  .items-table-wrap { border: 1px solid rgba(8, 0, 89, 0.1); border-radius: 12px; overflow: hidden; }
  .items-table thead th { background: #f4f6ff; color: #080059; font-size: 0.82rem; font-weight: 700; }
  .items-table .form-control { min-height: 38px; font-size: 0.9rem; }
  .line-total { font-weight: 600; color: #080059; white-space: nowrap; }
  .item-sr {
    font-weight: 700;
    color: #080059;
    white-space: nowrap;
  }
  .line-item-row.is-sub-heading {
    background: rgba(8, 0, 89, 0.04);
  }
  .line-item-row.is-sub-heading .item-description {
    font-weight: 700;
  }
  .line-item-row.is-sub-item .item-description {
    padding-left: 0.75rem;
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
  .btn-add-line {
    border: 1px dashed rgba(8, 0, 89, 0.3);
    border-radius: 10px;
    background: #fff;
    color: #080059;
    font-weight: 600;
    padding: 0.45rem 0.85rem;
  }
  .btn-add-line.btn-sub-heading {
    border-color: rgba(8, 0, 89, 0.45);
    background: rgba(8, 0, 89, 0.04);
  }
  .btn-add-line.btn-sub-item {
    border-style: solid;
  }
  .btn-remove-line {
    width: 34px; height: 34px; border-radius: 8px;
    border: 1px solid rgba(217, 75, 110, 0.3); background: #fff; color: #d94b6e;
  }
  .item-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.45rem;
  }
  .form-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.6rem;
    justify-content: flex-end;
    margin-top: 1.25rem;
  }
</style>
@endsection

@section('content')
@php
  $isEdit = !is_null($quotation);
  $initialItems = $isEdit
    ? $quotation->items->map(function ($item) {
        return [
          'item_type' => $item->item_type ?: 'main_item',
          'display_number' => $item->display_number,
          'description' => $item->description,
          'unit' => $item->unit,
          'qty' => $item->qty,
          'unit_price' => $item->unit_price,
        ];
      })->values()
    : [];
@endphp
<section class="quotations-content">
  <div class="quotations-card">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
      <div>
        <h5 class="quotations-title">{{ $pageTitle }}</h5>
        <p class="quotations-subtitle">
          {{ $isEdit ? 'Update quotation details and regenerate the PDF' : 'Fill in company details and line items to create a quotation' }}
        </p>
      </div>
      <a href="{{ route('dashboard.quotations.index') }}" class="btn btn-back">
        <i class="fa-solid fa-arrow-left me-1"></i>Back to Quotations
      </a>
    </div>

    <form id="quotationForm" novalidate>
      <input type="hidden" id="editingQuotationId" value="{{ $isEdit ? $quotation->id : '' }}">
      <input type="hidden" id="previewQuotationNumber" value="{{ $isEdit ? $quotation->quotation_number : '' }}">

      <div class="row g-2 mb-3">
        <div class="col-md-4">
          <label class="form-label">Company Name</label>
          <input type="text" class="form-control" id="companyName" placeholder="Client company name" value="{{ $isEdit ? $quotation->company_name : '' }}">
          <span class="text-danger error-message" id="companyName-error"></span>
        </div>
        <div class="col-md-4">
          <label class="form-label">Contact Person <span class="text-muted">(optional)</span></label>
          <input type="text" class="form-control" id="contactPerson" placeholder="Contact person name" value="{{ $isEdit ? ($quotation->contact_person ?? '') : '' }}">
          <span class="text-danger error-message" id="contactPerson-error"></span>
        </div>
        <div class="col-md-4">
          <label class="form-label">Phone Number <span class="text-muted">(optional)</span></label>
          <input type="text" class="form-control" id="contactPhone" placeholder="e.g. +971 52 738 2675" value="{{ $isEdit ? ($quotation->contact_phone ?? '') : '' }}">
          <span class="text-danger error-message" id="contactPhone-error"></span>
        </div>
        <div class="col-md-4">
          <label class="form-label">Quotation Date</label>
          <input type="date" class="form-control" id="quotationDate" value="{{ $isEdit ? $quotation->quotation_date->format('Y-m-d') : now()->format('Y-m-d') }}">
          <span class="text-danger error-message" id="quotationDate-error"></span>
        </div>
        <div class="col-md-8">
          <label class="form-label">Notes <span class="text-muted">(optional)</span></label>
          <input type="text" class="form-control" id="quotationNotes" placeholder="Additional notes" value="{{ $isEdit ? ($quotation->notes ?? '') : '' }}">
          <span class="text-danger error-message" id="quotationNotes-error"></span>
        </div>
        <div class="col-12">
          <label class="form-label">Subject <span class="text-muted">(optional)</span></label>
          <input type="text" class="form-control" id="quotationSubject" placeholder="Quotation subject" value="{{ $isEdit ? ($quotation->subject ?? '') : '' }}">
          <span class="text-danger error-message" id="quotationSubject-error"></span>
        </div>
      </div>

      <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
        <label class="form-label mb-0 fw-semibold" style="color:#080059;">Line Items</label>
        <div class="item-actions">
          <button type="button" class="btn btn-add-line btn-sm" id="addMainItemBtn">
            <i class="fa-solid fa-plus me-1"></i>Add Main Item
          </button>
          <button type="button" class="btn btn-add-line btn-sub-heading btn-sm" id="addSubHeadingBtn">
            <i class="fa-solid fa-heading me-1"></i>Add Sub-Heading
          </button>
          <button type="button" class="btn btn-add-line btn-sub-item btn-sm" id="addSubItemBtn">
            <i class="fa-solid fa-list-ol me-1"></i>Add Sub-Item
          </button>
        </div>
      </div>
      <span class="text-danger error-message d-block mb-2" id="items-error"></span>

      <div class="items-table-wrap mb-3">
        <table class="table items-table mb-0">
          <thead>
            <tr>
              <th style="width:8%;">#</th>
              <th style="width:34%;">Description</th>
              <th style="width:12%;">Unit</th>
              <th style="width:12%;">Qty</th>
              <th style="width:14%;">Unit Price</th>
              <th style="width:12%;">Total</th>
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

      <div class="form-actions">
        <a href="{{ route('dashboard.quotations.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button class="btn btn-preview" id="previewQuotationBtn" type="button">
          <i class="fa-regular fa-file-pdf me-1"></i>Preview PDF
        </button>
        <button class="btn btn-save" id="saveQuotationBtn" type="button">
          {{ $isEdit ? 'Update Quotation' : 'Save Quotation' }}
        </button>
      </div>
    </form>
  </div>
</section>
@endsection

@section('scripts')
<script>
  $(function () {
    const isEdit = !!$("#editingQuotationId").val();
    const initialItems = @json($initialItems);

    function escapeAttr(value) {
      return String(value ?? "")
        .replace(/&/g, "&amp;")
        .replace(/"/g, "&quot;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;");
    }

    function formatMoney(value) {
      return Number(value || 0).toFixed(2);
    }

    function lineItemRow(item = {}) {
      const type = item.item_type || "main_item";
      const isHeading = type === "sub_heading";
      const rowClass = type === "sub_heading"
        ? "is-sub-heading"
        : (type === "sub_item" ? "is-sub-item" : "");
      const disabledAttr = isHeading ? "disabled" : "";
      const qtyValue = isHeading ? 0 : (item.qty ?? 1);
      const priceValue = isHeading ? 0 : (item.unit_price ?? 0);
      const unitValue = isHeading ? "" : (item.unit ?? "");
      const placeholder = isHeading ? "Sub-heading title" : "Item description";

      return `
        <tr class="line-item-row ${rowClass}" data-item-type="${type}">
          <td class="align-middle">
            <span class="item-sr">${escapeAttr(item.display_number || "")}</span>
            <input type="hidden" class="item-type" value="${type}">
            <input type="hidden" class="item-display-number" value="${escapeAttr(item.display_number || "")}">
          </td>
          <td>
            <input type="text" class="form-control item-description" value="${escapeAttr(item.description ?? "")}" placeholder="${placeholder}">
          </td>
          <td>
            <input type="text" class="form-control item-unit" value="${escapeAttr(unitValue)}" placeholder="e.g. Job" ${disabledAttr}>
          </td>
          <td>
            <input type="number" min="0" step="0.01" class="form-control item-qty" value="${qtyValue}" ${disabledAttr}>
          </td>
          <td>
            <input type="number" min="0" step="0.01" class="form-control item-unit-price" value="${priceValue}" ${disabledAttr}>
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

    function renumberItems() {
      let topLevel = 0;
      let subIndex = 0;
      let inHeading = false;

      $("#lineItemsBody .line-item-row").each(function () {
        const $row = $(this);
        let type = $row.data("item-type") || $row.find(".item-type").val() || "main_item";

        if (type === "sub_item" && !inHeading) {
          type = "main_item";
          $row.data("item-type", type);
          $row.find(".item-type").val(type);
          $row.removeClass("is-sub-item is-sub-heading");
          $row.find(".item-unit, .item-qty, .item-unit-price").prop("disabled", false);
        }

        if (type === "main_item" || type === "sub_heading") {
          topLevel += 1;
          subIndex = 0;
          inHeading = type === "sub_heading";
          $row.find(".item-sr").text(String(topLevel));
          $row.find(".item-display-number").val(String(topLevel));
        } else {
          subIndex += 1;
          const number = topLevel + "." + subIndex;
          $row.find(".item-sr").text(number);
          $row.find(".item-display-number").val(number);
        }
      });
    }

    function updateLineTotal($row) {
      const type = $row.data("item-type") || $row.find(".item-type").val();
      if (type === "sub_heading") {
        $row.find(".line-total").text("—");
        updateGrandTotal();
        return;
      }

      const qty = parseFloat($row.find(".item-qty").val()) || 0;
      const unitPrice = parseFloat($row.find(".item-unit-price").val()) || 0;
      $row.find(".line-total").text(formatMoney(qty * unitPrice));
      updateGrandTotal();
    }

    function updateGrandTotal() {
      let grandTotal = 0;
      $("#lineItemsBody .line-item-row").each(function () {
        const type = $(this).data("item-type") || $(this).find(".item-type").val();
        if (type === "sub_heading") {
          return;
        }
        const qty = parseFloat($(this).find(".item-qty").val()) || 0;
        const unitPrice = parseFloat($(this).find(".item-unit-price").val()) || 0;
        grandTotal += qty * unitPrice;
      });
      $("#grandTotalDisplay").text(formatMoney(grandTotal));
    }

    function hasSubHeading() {
      let found = false;
      $("#lineItemsBody .line-item-row").each(function () {
        const type = $(this).data("item-type") || $(this).find(".item-type").val();
        if (type === "sub_heading") {
          found = true;
          return false;
        }
      });
      return found;
    }

    function addLineItem(item = {}) {
      $("#lineItemsBody").append(lineItemRow(item));
      const $row = $("#lineItemsBody .line-item-row").last();
      renumberItems();
      updateLineTotal($row);
    }

    function collectItems() {
      const items = [];
      $("#lineItemsBody .line-item-row").each(function () {
        const type = $(this).data("item-type") || $(this).find(".item-type").val() || "main_item";
        const isHeading = type === "sub_heading";
        items.push({
          item_type: type,
          display_number: $(this).find(".item-display-number").val(),
          description: $(this).find(".item-description").val(),
          unit: isHeading ? "" : $(this).find(".item-unit").val(),
          qty: isHeading ? 0 : $(this).find(".item-qty").val(),
          unit_price: isHeading ? 0 : $(this).find(".item-unit-price").val(),
        });
      });
      return items;
    }

    function payload() {
      const data = {
        _token: "{{ csrf_token() }}",
        company_name: $("#companyName").val(),
        contact_person: $("#contactPerson").val(),
        contact_phone: $("#contactPhone").val(),
        subject: $("#quotationSubject").val(),
        quotation_date: $("#quotationDate").val(),
        notes: $("#quotationNotes").val(),
        items: collectItems(),
      };

      const previewNumber = $("#previewQuotationNumber").val();
      if (previewNumber) {
        data.quotation_number = previewNumber;
      }

      return data;
    }

    function setErrors(errors = {}) {
      $(".error-message").html("");
      $("#lineItemsBody .form-control").removeClass("is-invalid");

      if (errors.company_name) $("#companyName-error").text(errors.company_name[0]);
      if (errors.contact_person) $("#contactPerson-error").text(errors.contact_person[0]);
      if (errors.contact_phone) $("#contactPhone-error").text(errors.contact_phone[0]);
      if (errors.subject) $("#quotationSubject-error").text(errors.subject[0]);
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
            $row.find(".item-" + field.replace(/_/g, "-")).addClass("is-invalid");
          }
        }
      });
    }

    function handlePreviewError(xhr) {
      if (xhr.responseJSON && xhr.responseJSON.errors) {
        setErrors(xhr.responseJSON.errors);
        return;
      }

      if (xhr.responseJSON && xhr.responseJSON.message) {
        alert(xhr.responseJSON.message);
        return;
      }

      if (xhr.response && xhr.response instanceof Blob) {
        const reader = new FileReader();
        reader.onload = function () {
          try {
            const response = JSON.parse(reader.result);
            if (response.errors) {
              setErrors(response.errors);
            } else if (response.message) {
              alert(response.message);
            } else {
              alert("Unable to generate preview. Please check the form and try again.");
            }
          } catch (e) {
            alert("Unable to generate preview. Please check the form and try again.");
          }
        };
        reader.readAsText(xhr.response);
        return;
      }

      alert("Unable to generate preview. Please check the form and try again.");
    }

    $("#addMainItemBtn").on("click", function () {
      addLineItem({ item_type: "main_item" });
    });

    $("#addSubHeadingBtn").on("click", function () {
      addLineItem({ item_type: "sub_heading", qty: 0, unit_price: 0, unit: "" });
    });

    $("#addSubItemBtn").on("click", function () {
      if (!hasSubHeading()) {
        alert("Please add a sub-heading first, then add sub-items under it.");
        return;
      }
      addLineItem({ item_type: "sub_item" });
    });

    $("#lineItemsBody").on("input", ".item-qty, .item-unit-price", function () {
      updateLineTotal($(this).closest(".line-item-row"));
    });

    $("#lineItemsBody").on("click", ".remove-line-item", function () {
      const rows = $("#lineItemsBody .line-item-row");
      if (rows.length <= 1) {
        const $row = $(this).closest(".line-item-row");
        $row.find(".item-description").val("");
        if (($row.data("item-type") || $row.find(".item-type").val()) !== "sub_heading") {
          $row.find(".item-unit").val("");
          $row.find(".item-qty").val(1);
          $row.find(".item-unit-price").val(0);
        }
        updateLineTotal($row);
        return;
      }
      $(this).closest(".line-item-row").remove();
      renumberItems();
      updateGrandTotal();
    });

    $("#previewQuotationBtn").on("click", function () {
      const btn = $(this);
      btn.prop("disabled", true).html('<i class="fa-solid fa-spinner fa-spin"></i> Preparing...');

      $.ajax({
        url: "{{ route('dashboard.quotations.preview') }}",
        type: "POST",
        data: payload(),
        xhrFields: { responseType: "blob" },
        success: function (blob, status, xhr) {
          const contentType = xhr.getResponseHeader("content-type") || "";

          if (contentType.indexOf("application/json") !== -1) {
            const reader = new FileReader();
            reader.onload = function () {
              try {
                const response = JSON.parse(reader.result);
                if (response.errors) {
                  setErrors(response.errors);
                } else if (response.message) {
                  alert(response.message);
                }
              } catch (e) {
                alert("Unable to generate preview. Please check the form and try again.");
              }
            };
            reader.readAsText(blob);
            return;
          }

          const fileUrl = URL.createObjectURL(blob);
          window.open(fileUrl, "_blank");
          setTimeout(function () {
            URL.revokeObjectURL(fileUrl);
          }, 60000);
        },
        error: handlePreviewError,
        complete: function () {
          btn.prop("disabled", false).html('<i class="fa-regular fa-file-pdf me-1"></i>Preview PDF');
        }
      });
    });

    $("#saveQuotationBtn").on("click", function () {
      const btn = $(this);
      const quotationId = $("#editingQuotationId").val();
      const saveLabel = isEdit ? "Update Quotation" : "Save Quotation";

      btn.prop("disabled", true).html('<i class="fa-solid fa-spinner fa-spin"></i> Saving...');
      $(".error-message").html("");
      $("#lineItemsBody .form-control").removeClass("is-invalid");

      $.ajax({
        url: isEdit ? `{{ url('/dashboard/quotations') }}/${quotationId}` : "{{ route('dashboard.quotations.store') }}",
        type: isEdit ? "PUT" : "POST",
        data: payload(),
        success: function (response) {
          if (response.file_url) {
            window.open(response.file_url, "_blank");
          }
          window.location.href = "{{ route('dashboard.quotations.index') }}";
        },
        error: function (xhr) {
          if (xhr.responseJSON?.errors) {
            setErrors(xhr.responseJSON.errors);
          }
        },
        complete: function () {
          btn.prop("disabled", false).html(saveLabel);
        },
      });
    });

    if (initialItems.length) {
      initialItems.forEach(function (item) {
        addLineItem(item);
      });
    } else {
      addLineItem({ item_type: "main_item" });
    }
  });
</script>
@endsection
