@extends('layouts.dashboard')

@section('title', 'Salaries')

@section('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<style>
  .salaries-content {
    padding: 1.3rem;
  }

  .salaries-card {
    border-radius: 16px;
    border: 1px solid rgba(8, 0, 89, 0.08);
    background: rgba(255, 255, 255, 0.72);
    backdrop-filter: blur(10px);
    box-shadow: 0 12px 30px rgba(8, 0, 89, 0.08);
    padding: 1rem;
  }

  .salaries-title {
    color: #080059;
    font-weight: 700;
    margin: 0 0 0.3rem;
  }

  .salaries-subtitle {
    color: #6f7294;
    margin: 0;
    font-size: 0.9rem;
  }

  .filter-box {
    margin-top: 1rem;
    border: 1px solid rgba(8, 0, 89, 0.08);
    border-radius: 14px;
    padding: 0.9rem;
    background: rgba(255, 255, 255, 0.6);
  }

  .filter-box .form-control,
  .filter-box .form-select {
    border-radius: 10px;
    border: 1px solid rgba(8, 0, 89, 0.16);
    min-height: 42px;
  }

  .filter-box .form-control:focus,
  .filter-box .form-select:focus {
    border-color: #eabc73;
    box-shadow: 0 0 0 0.2rem rgba(234, 188, 115, 0.22);
  }
  .filter-box .select2-container .select2-selection--single {
    border-radius: 10px !important;
    border: 1px solid rgba(8, 0, 89, 0.16) !important;
    min-height: 42px;
    background: #fff;
  }
  .filter-box .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 40px;
    padding-left: 0.85rem;
    color: #2b2f55;
  }
  .filter-box .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px;
    right: 8px;
  }
  .filter-box .select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #eabc73 !important;
    box-shadow: 0 0 0 0.2rem rgba(234, 188, 115, 0.22);
  }

  .btn-search-salary {
    border: none;
    border-radius: 12px;
    background: linear-gradient(120deg, #eabc73, #f1d19a);
    color: #080059;
    font-weight: 700;
    min-height: 42px;
    padding: 0.55rem 0.95rem;
  }

  .salary-cards {
    margin-top: 1rem;
  }
  .search-results-hidden {
    display: none !important;
  }

  .salary-card {
    border-radius: 14px;
    border: 1px solid rgba(8, 0, 89, 0.08);
    background: #fff;
    padding: 0.95rem;
    height: 100%;
  }

  .salary-card h6 {
    margin: 0;
    color: #080059;
    font-weight: 700;
    font-size: 0.92rem;
  }

  .salary-card .amount {
    margin-top: 0.55rem;
    margin-bottom: 0;
    font-size: 1.2rem;
    font-weight: 800;
    color: #080059;
  }
  .summary-mini {
    margin-top: 0.5rem;
    margin-bottom: 0;
    color: #5f638c;
    font-size: 0.85rem;
  }

  .card-action-eye {
    border: 1px solid rgba(8, 0, 89, 0.16);
    border-radius: 10px;
    background: #fff;
    color: #080059;
    width: 34px;
    height: 34px;
  }
  .card-action-group {
    display: inline-flex;
    gap: 0.35rem;
  }

  .search-error {
    display: block;
    margin-top: 0.25rem;
    color: #d94b6e;
    font-size: 0.82rem;
  }

  .results-meta {
    margin-top: 0.85rem;
    color: #5f638c;
    font-size: 0.88rem;
  }
  .salary-slip-wrap {
    margin-top: 1rem;
    border: 1px solid rgba(8, 0, 89, 0.08);
    border-radius: 14px;
    background: #fff;
    overflow: hidden;
  }
  .salary-slip-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.85rem 0.95rem;
    border-bottom: 1px solid rgba(8, 0, 89, 0.08);
    background: rgba(248, 248, 255, 0.8);
  }
  .salary-slip-head h6 {
    margin: 0;
    color: #080059;
    font-weight: 700;
  }
  .salary-slip-head p {
    margin: 0;
    color: #6f7294;
    font-size: 0.82rem;
  }
  .btn-generate-slip {
    border: none;
    border-radius: 10px;
    background: linear-gradient(120deg, #080059, #1c109f);
    color: #fff;
    font-weight: 600;
    font-size: 0.85rem;
    padding: 0.5rem 0.85rem;
    white-space: nowrap;
  }
  .slip-actions {
    display: flex;
    align-items: flex-end;
    gap: 0.55rem;
    flex-wrap: wrap;
  }
  .advance-deduction-wrap {
    min-width: 180px;
  }
  .advance-deduction-wrap label {
    display: block;
    margin-bottom: 0.2rem;
    color: #5f638c;
    font-size: 0.76rem;
    font-weight: 600;
  }
  .advance-deduction-wrap .form-control {
    min-height: 36px;
    border-radius: 9px;
    border: 1px solid rgba(8, 0, 89, 0.16);
    font-size: 0.85rem;
  }
  .salary-slip-table th,
  .salary-slip-table td {
    vertical-align: middle;
    font-size: 0.87rem;
    white-space: nowrap;
  }
  .day-status {
    display: inline-block;
    border-radius: 999px;
    padding: 0.2rem 0.55rem;
    font-size: 0.74rem;
    font-weight: 700;
  }
  .day-status.present {
    background: rgba(28, 166, 115, 0.16);
    color: #1f7a58;
  }
  .day-status.absent {
    background: rgba(220, 76, 100, 0.14);
    color: #b13a50;
  }

  .entries-modal .modal-content {
    border-radius: 16px;
    border: 1px solid rgba(8, 0, 89, 0.09);
    box-shadow: 0 22px 48px rgba(8, 0, 89, 0.26);
    overflow: hidden;
  }

  .entries-modal .modal-header {
    border-bottom: 1px solid rgba(8, 0, 89, 0.08);
    background: linear-gradient(130deg, #080059, #1c109f);
    color: #fff;
  }

  .entries-table th,
  .entries-table td {
    vertical-align: middle;
  }
  .status-chip {
    display: inline-block;
    border-radius: 999px;
    padding: 0.22rem 0.58rem;
    font-size: 0.76rem;
    font-weight: 700;
  }
  .status-taken {
    background: rgba(220, 76, 100, 0.14);
    color: #b13a50;
  }
  .status-given {
    background: rgba(28, 166, 115, 0.16);
    color: #1f7a58;
  }

  .empty-note {
    color: #7b7fa6;
    text-align: center;
    padding: 0.7rem;
  }
  .salary-details-list .list-group-item {
    border-color: rgba(8, 0, 89, 0.08);
    font-size: 0.92rem;
  }
  .salary-details-list .value {
    font-weight: 700;
    color: #080059;
  }
  .absent-dates {
    margin-top: 0.75rem;
    border: 1px dashed rgba(8, 0, 89, 0.16);
    border-radius: 10px;
    padding: 0.65rem;
    background: rgba(248, 248, 255, 0.8);
  }
  .absent-dates h6 {
    margin: 0 0 0.35rem;
    color: #080059;
    font-size: 0.88rem;
    font-weight: 700;
  }
  .absent-dates p {
    margin: 0;
    color: #5f638c;
    font-size: 0.85rem;
    line-height: 1.45;
  }
  @media (max-width: 768px) {
    .salary-slip-head {
      align-items: flex-start;
      flex-direction: column;
      gap: 0.6rem;
    }
    .advance-deduction-wrap {
      width: 100%;
    }
  }
</style>
@endsection

@section('content')
<section class="salaries-content">
  <div class="salaries-card">
    <h5 class="salaries-title">Salaries</h5>
    <p class="salaries-subtitle">Select user and month/year to calculate advance summary.</p>

    <div class="filter-box">
      <form id="salaryFilterForm" novalidate>
        <div class="row g-2 align-items-end">
          <div class="col-md-4">
            <label class="form-label">User</label>
            <select class="form-select filter-select2" id="salaryUserId" data-placeholder="Select user">
              <option value="">Select user</option>
              @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
              @endforeach
            </select>
            <span class="search-error" id="salaryUserId-error"></span>
          </div>
          <div class="col-md-3">
            <label class="form-label">Month</label>
            <select class="form-select filter-select2" id="salaryMonth" data-placeholder="Select month">
              <option value="">Select month</option>
              @for ($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
              @endfor
            </select>
            <span class="search-error" id="salaryMonth-error"></span>
          </div>
          <div class="col-md-2">
            <label class="form-label">Year</label>
            <input type="number" class="form-control" id="salaryYear" min="2000" max="2100" value="{{ now()->year }}">
            <span class="search-error" id="salaryYear-error"></span>
          </div>
          <div class="col-md-1">
            <label class="form-label">Allowed Off</label>
            <input type="number" class="form-control" id="salaryAllowedOff" min="0" max="31" value="4">
            <span class="search-error" id="salaryAllowedOff-error"></span>
          </div>
          <div class="col-md-2">
            <button type="button" class="btn btn-search-salary w-100" id="searchSalaryBtn">
              <i class="fa-solid fa-magnifying-glass me-1"></i>Search
            </button>
          </div>
        </div>
      </form>
    </div>

    <p class="results-meta" id="salaryResultsMeta">Run a search to view salary-related advance summary.</p>

    <div class="row g-3 salary-cards search-results-hidden" id="salaryCardsWrap">
      <div class="col-md-6">
        <div class="salary-card">
          <div class="d-flex justify-content-between align-items-center">
            <h6>Advance Summary</h6>
            <button type="button" class="card-action-eye" id="viewAdvanceEntriesBtn" title="View all advance entries">
              <i class="fa-regular fa-eye"></i>
            </button>
          </div>
          <p class="amount" id="netAdvanceAmount">0.00</p>
          <p class="summary-mini">
            Taken: <strong id="totalTakenAmount">0.00</strong> |
            Given: <strong id="totalGivenBackAmount">0.00</strong>
          </p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="salary-card">
          <div class="d-flex justify-content-between align-items-center">
            <h6>Salary of Month</h6>
            <div class="card-action-group">
              <button type="button" class="card-action-eye" id="viewSalaryDetailsBtn" title="View salary details">
                <i class="fa-regular fa-eye"></i>
              </button>
              <button type="button" class="card-action-eye" id="viewOvertimeDetailsBtn" title="View overtime details">
                <i class="fa-regular fa-clock"></i>
              </button>
            </div>
          </div>
          <p class="amount" id="finalSalaryAmount">0.00</p>
          <p class="summary-mini">
            Presents: <strong id="presentsCount">0</strong> |
            Off: <strong id="offsCount">0</strong> |
            OT Hrs: <strong id="overtimeHoursCount">0.00</strong>
          </p>
        </div>
      </div>
    </div>

    <div class="salary-slip-wrap search-results-hidden" id="salarySlipWrap">
      <div class="salary-slip-head">
        <div>
          <h6>Monthly Salary Slip Details</h6>
          <p>Day-by-day details with project, attendance and hours.</p>
        </div>
        <div class="slip-actions">
          <div class="advance-deduction-wrap">
            <label for="advanceDeductionAmount">Advance Deduction</label>
            <input type="number" class="form-control" id="advanceDeductionAmount" min="0" step="0.01" value="0">
            <span class="search-error" id="advanceDeductionAmount-error"></span>
          </div>
          <button type="button" class="btn btn-generate-slip" id="generateSalarySlipBtn">
            <i class="fa-solid fa-file-lines me-1"></i>Generate Salary Slip
          </button>
          <a href="javascript:void(0)" target="_blank" class="btn btn-generate-slip d-none" id="viewSalarySlipBtn">
            <i class="fa-regular fa-eye me-1"></i>View Salary Slip
          </a>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table salary-slip-table mb-0">
          <thead>
            <tr>
              <th>Date</th>
              <th>Status</th>
              <th>Project(s)</th>
              <th>Working Hours</th>
              <th>Overtime Hours</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody id="salarySlipBody">
            <tr>
              <td colspan="6" class="empty-note">Run a search to generate monthly salary slip details.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
@endsection

@section('modals')
<div class="modal fade entries-modal" id="entriesModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="entriesModalTitle">Advance Entries</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table entries-table mb-0">
            <thead>
              <tr>
                <th>Date</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Description</th>
              </tr>
            </thead>
            <tbody id="entriesModalBody"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade entries-modal" id="salaryDetailsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Salary Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="list-group salary-details-list">
          <li class="list-group-item d-flex justify-content-between"><span>Days in month</span><span class="value" id="detailDaysInMonth">0</span></li>
          <li class="list-group-item d-flex justify-content-between"><span>Presents</span><span class="value" id="detailPresents">0</span></li>
          <li class="list-group-item d-flex justify-content-between"><span>Offs</span><span class="value" id="detailOffs">0</span></li>
          <li class="list-group-item d-flex justify-content-between"><span>Allowed offs</span><span class="value" id="detailAllowedOff">4</span></li>
          <li class="list-group-item d-flex justify-content-between"><span>Extra offs</span><span class="value" id="detailExtraOff">0</span></li>
          <li class="list-group-item d-flex justify-content-between"><span>Single day salary</span><span class="value" id="detailSingleDaySalary">0.00</span></li>
          <li class="list-group-item d-flex justify-content-between"><span>Hourly rate</span><span class="value" id="detailHourlyRate">0.00</span></li>
          <li class="list-group-item d-flex justify-content-between"><span>Total overtime hours</span><span class="value" id="detailOvertimeHours">0.00</span></li>
          <li class="list-group-item d-flex justify-content-between"><span>Adjusted overtime hours</span><span class="value" id="detailAdjustedOvertimeHours">0.00</span></li>
          <li class="list-group-item d-flex justify-content-between"><span>Overtime amount</span><span class="value" id="detailOvertimeAmount">0.00</span></li>
          <li class="list-group-item d-flex justify-content-between"><span>Deduction</span><span class="value" id="detailDeduction">0.00</span></li>
          <li class="list-group-item d-flex justify-content-between"><span>Monthly salary</span><span class="value" id="detailMonthlySalary">0.00</span></li>
          <li class="list-group-item d-flex justify-content-between"><span>Final salary</span><span class="value" id="detailFinalSalary">0.00</span></li>
        </ul>
        <div class="absent-dates">
          <h6>Absent Dates</h6>
          <p id="detailAbsentDates">No absent dates.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade entries-modal" id="overtimeDetailsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Overtime Working Hour Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table entries-table mb-0">
            <thead>
              <tr>
                <th>Date</th>
                <th>Project(s)</th>
                <th>OT Hours</th>
                <th>Adjusted OT Hours</th>
                <th>Description</th>
              </tr>
            </thead>
            <tbody id="overtimeDetailsBody"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(function () {
    const entriesModal = new bootstrap.Modal(document.getElementById("entriesModal"));
    const salaryDetailsModal = new bootstrap.Modal(document.getElementById("salaryDetailsModal"));
    const overtimeDetailsModal = new bootstrap.Modal(document.getElementById("overtimeDetailsModal"));
    let cachedAllEntries = [];
    let cachedSalarySummary = null;
    let cachedDailyDetails = [];
    let cachedNetAdvance = 0;
    let cachedSalaryOfMonth = 0;
    let cachedSalarySlipUrl = null;

    $(".filter-select2").each(function () {
      const $el = $(this);
      $el.select2({
        width: "100%",
        placeholder: $el.data("placeholder") || "Select option",
        allowClear: false,
      });
    });

    function clearErrors() {
      $("#salaryUserId-error").text("");
      $("#salaryMonth-error").text("");
      $("#salaryYear-error").text("");
      $("#salaryAllowedOff-error").text("");
      $("#advanceDeductionAmount-error").text("");
    }

    function toggleSearchSections(show) {
      $("#salaryCardsWrap").toggleClass("search-results-hidden", !show);
      $("#salarySlipWrap").toggleClass("search-results-hidden", !show);
    }

    function toggleSlipActions(hasExistingSlip) {
      if (hasExistingSlip && cachedSalarySlipUrl) {
        $("#generateSalarySlipBtn").addClass("d-none");
        $("#viewSalarySlipBtn").removeClass("d-none").attr("href", cachedSalarySlipUrl);
        $(".advance-deduction-wrap").addClass("d-none");
      } else {
        $("#viewSalarySlipBtn").addClass("d-none").attr("href", "javascript:void(0)");
        $("#generateSalarySlipBtn").removeClass("d-none");
        $(".advance-deduction-wrap").removeClass("d-none");
      }
    }

    function formatAmount(value) {
      return Number(value || 0).toFixed(2);
    }

    function renderEntries(entries) {
      const body = $("#entriesModalBody");
      body.html("");

      if (!entries.length) {
        body.html('<tr><td colspan="4" class="empty-note">No entries found for selected filter.</td></tr>');
        return;
      }

      entries.forEach((entry) => {
        body.append(`
          <tr>
            <td>${entry.date ?? "-"}</td>
            <td>${formatAmount(entry.amount)}</td>
            <td><span class="status-chip ${entry.status_class || ""}">${entry.status_label || "-"}</span></td>
            <td>${entry.description ?? "-"}</td>
          </tr>
        `);
      });
    }

    function renderSalarySlip(details) {
      const body = $("#salarySlipBody");
      body.html("");

      if (!details || !details.length) {
        body.html('<tr><td colspan="6" class="empty-note">No salary slip details found for selected filter.</td></tr>');
        return;
      }

      details.forEach((day) => {
        body.append(`
          <tr>
            <td>${day.date ?? "-"}</td>
            <td><span class="day-status ${day.status || "absent"}">${(day.status || "").toUpperCase() || "-"}</span></td>
            <td>${day.projects ?? "-"}</td>
            <td>${formatAmount(day.working_hours)}</td>
            <td>${formatAmount(day.overtime_hours)}</td>
            <td>${day.description ?? "-"}</td>
          </tr>
        `);
      });
    }

    function renderOvertimeDetails(details) {
      const body = $("#overtimeDetailsBody");
      body.html("");

      const overtimeDays = (details || []).filter((day) => Number(day.overtime_hours || 0) > 0);

      if (!overtimeDays.length) {
        body.html('<tr><td colspan="5" class="empty-note">No overtime entries found for selected filter.</td></tr>');
        return;
      }

      overtimeDays.forEach((day) => {
        const otHours = Number(day.overtime_hours || 0);
        const adjustedOtHours = otHours > 3 ? (otHours + 2) : otHours;

        body.append(`
          <tr>
            <td>${day.date ?? "-"}</td>
            <td>${day.projects ?? "-"}</td>
            <td>${formatAmount(otHours)}</td>
            <td>${formatAmount(adjustedOtHours)}</td>
            <td>${day.description ?? "-"}</td>
          </tr>
        `);
      });
    }

    function showEntries() {
      $("#entriesModalTitle").text("Advance Summary Entries");
      renderEntries(cachedAllEntries);
      entriesModal.show();
    }

    $("#viewAdvanceEntriesBtn").on("click", function () {
      showEntries();
    });

    $("#viewSalaryDetailsBtn").on("click", function () {
      if (!cachedSalarySummary) {
        return;
      }

      $("#detailDaysInMonth").text(cachedSalarySummary.days_in_month ?? 0);
      $("#detailPresents").text(cachedSalarySummary.presents ?? 0);
      $("#detailOffs").text(cachedSalarySummary.offs ?? 0);
      $("#detailAllowedOff").text(cachedSalarySummary.allowed_off ?? 4);
      $("#detailExtraOff").text(cachedSalarySummary.extra_off ?? 0);
      $("#detailSingleDaySalary").text(formatAmount(cachedSalarySummary.single_day_salary));
      $("#detailHourlyRate").text(formatAmount(cachedSalarySummary.hourly_rate));
      $("#detailOvertimeHours").text(formatAmount(cachedSalarySummary.overtime_hours));
      $("#detailAdjustedOvertimeHours").text(formatAmount(cachedSalarySummary.adjusted_overtime_hours));
      $("#detailOvertimeAmount").text(formatAmount(cachedSalarySummary.overtime_amount));
      $("#detailDeduction").text(formatAmount(cachedSalarySummary.deduction));
      $("#detailMonthlySalary").text(formatAmount(cachedSalarySummary.monthly_salary));
      $("#detailFinalSalary").text(formatAmount(cachedSalarySummary.final_salary));
      $("#detailAbsentDates").text(
        (cachedSalarySummary.absent_dates && cachedSalarySummary.absent_dates.length)
          ? cachedSalarySummary.absent_dates.join(", ")
          : "No absent dates."
      );
      salaryDetailsModal.show();
    });

    $("#viewOvertimeDetailsBtn").on("click", function () {
      renderOvertimeDetails(cachedDailyDetails);
      overtimeDetailsModal.show();
    });

    $("#generateSalarySlipBtn").on("click", function () {
      const btn = $(this);
      $("#advanceDeductionAmount-error").text("");
      const deductionInput = Number($("#advanceDeductionAmount").val() || 0);

      if (deductionInput < 0) {
        $("#advanceDeductionAmount-error").text("Advance deduction cannot be negative.");
        return;
      }

      if (deductionInput > cachedNetAdvance) {
        $("#advanceDeductionAmount-error").text("Advance deduction cannot be greater than total advance.");
        return;
      }

      if (deductionInput > cachedSalaryOfMonth) {
        $("#advanceDeductionAmount-error").text("Advance deduction cannot be greater than salary of month.");
        return;
      }

      btn.prop("disabled", true).html('<i class="fa-solid fa-spinner fa-spin me-1"></i>Generating');
      $.ajax({
        url: "{{ route('dashboard.payments.salaries.generate-slip') }}",
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}",
          user_id: $("#salaryUserId").val(),
          month: $("#salaryMonth").val(),
          year: $("#salaryYear").val(),
          allowed_off: $("#salaryAllowedOff").val() || "4",
          advance_deduction: deductionInput.toFixed(2),
        },
        success: function (response) {
          if (response.file_url) {
            window.open(response.file_url, "_blank");
            cachedSalarySlipUrl = response.file_url;
            toggleSlipActions(true);
          }
        },
        error: function (xhr) {
          cachedSalarySlipUrl = null;
          toggleSlipActions(false);
          if (xhr.responseJSON?.errors) {
            const errors = xhr.responseJSON.errors;
            if (errors.user_id) $("#salaryUserId-error").text(errors.user_id[0]);
            if (errors.month) $("#salaryMonth-error").text(errors.month[0]);
            if (errors.year) $("#salaryYear-error").text(errors.year[0]);
            if (errors.allowed_off) $("#salaryAllowedOff-error").text(errors.allowed_off[0]);
            if (errors.advance_deduction) $("#advanceDeductionAmount-error").text(errors.advance_deduction[0]);
          }
        },
        complete: function () {
          btn.prop("disabled", false).html('<i class="fa-solid fa-file-lines me-1"></i>Generate Salary Slip');
        },
      });
    });

    $("#searchSalaryBtn").on("click", function () {
      const btn = $(this);
      clearErrors();
      toggleSearchSections(false);

      btn.prop("disabled", true).html('<i class="fa-solid fa-spinner fa-spin me-1"></i>Searching');

      $.ajax({
        url: "{{ route('dashboard.payments.salaries.summary') }}",
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}",
          user_id: $("#salaryUserId").val(),
          month: $("#salaryMonth").val(),
          year: $("#salaryYear").val(),
          allowed_off: $("#salaryAllowedOff").val(),
        },
        success: function (response) {
          const user = response.user || {};
          const entries = response.entries || [];
          const existingSalary = response.existing_salary || null;
          cachedSalarySummary = response.salary_summary || null;
          cachedDailyDetails = response.daily_details || [];
          cachedSalarySlipUrl = existingSalary?.file_url || null;

          cachedAllEntries = entries.map((entry) => ({
            ...entry,
            status_label: entry.status === "given" ? "Taken" : "Given",
            status_class: entry.status === "given" ? "status-taken" : "status-given",
          })).sort((a, b) => {
            const [ad, am, ay] = String(a.date || "").split("/");
            const [bd, bm, by] = String(b.date || "").split("/");
            const dateA = new Date(`${ay}-${am}-${ad}`);
            const dateB = new Date(`${by}-${bm}-${bd}`);
            return dateB - dateA;
          });

          const totalTaken = cachedAllEntries
            .filter((entry) => entry.status === "given")
            .reduce((sum, entry) => sum + Number(entry.amount || 0), 0);

          const totalGivenBack = cachedAllEntries
            .filter((entry) => entry.status === "received")
            .reduce((sum, entry) => sum + Number(entry.amount || 0), 0);
          const netAdvance = totalTaken - totalGivenBack;

          $("#totalTakenAmount").text(formatAmount(totalTaken));
          $("#totalGivenBackAmount").text(formatAmount(totalGivenBack));
          $("#netAdvanceAmount").text(formatAmount(netAdvance));
          $("#finalSalaryAmount").text(formatAmount(cachedSalarySummary?.final_salary));
          $("#presentsCount").text(cachedSalarySummary?.presents ?? 0);
          $("#offsCount").text(cachedSalarySummary?.offs ?? 0);
          $("#overtimeHoursCount").text(formatAmount(cachedSalarySummary?.overtime_hours));
          cachedNetAdvance = Math.max(netAdvance, 0);
          cachedSalaryOfMonth = Number(cachedSalarySummary?.final_salary || 0);
          const suggestedDeduction = Math.min(cachedNetAdvance, cachedSalaryOfMonth);
          $("#advanceDeductionAmount").val(formatAmount(suggestedDeduction));
          $("#advanceDeductionAmount-error").text("");
          renderSalarySlip(cachedDailyDetails);

          const monthLabel = $("#salaryMonth option:selected").text();
          $("#salaryResultsMeta").text(
            `${user.name ?? "User"} | ${monthLabel} ${$("#salaryYear").val()} | Base Salary: ${formatAmount(user.salary)}`
          );
          if (existingSalary?.generated_at) {
            $("#salaryResultsMeta").text(
              `${user.name ?? "User"} | ${monthLabel} ${$("#salaryYear").val()} | Salary slip generated: ${existingSalary.generated_at}`
            );
          }
          toggleSlipActions(!!existingSalary);
          toggleSearchSections(true);
        },
        error: function (xhr) {
          toggleSearchSections(false);
          if (xhr.responseJSON?.errors) {
            const errors = xhr.responseJSON.errors;
            if (errors.user_id) $("#salaryUserId-error").text(errors.user_id[0]);
            if (errors.month) $("#salaryMonth-error").text(errors.month[0]);
            if (errors.year) $("#salaryYear-error").text(errors.year[0]);
            if (errors.allowed_off) $("#salaryAllowedOff-error").text(errors.allowed_off[0]);
          }
        },
        complete: function () {
          btn.prop("disabled", false).html('<i class="fa-solid fa-magnifying-glass me-1"></i>Search');
        },
      });
    });

  });
</script>
@endsection
