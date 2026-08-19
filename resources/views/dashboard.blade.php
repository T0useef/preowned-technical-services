@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<style>
  .quick-action-btn {
    border-radius: 12px;
    font-weight: 700;
    padding: 0.55rem 1rem;
  }
  .quick-action-btn.primary {
    background: linear-gradient(120deg, #eabc73, #f2d39e);
    color: #080059;
    border: none;
  }
  .quick-action-btn.secondary {
    background: rgba(255, 255, 255, 0.12);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.45);
  }
  .hours-modal .modal-content {
    border-radius: 16px;
    border: 1px solid rgba(8, 0, 89, 0.12);
    box-shadow: 0 24px 48px rgba(8, 0, 89, 0.25);
    overflow: hidden;
  }
  .hours-modal .modal-header {
    border-bottom: 1px solid rgba(8, 0, 89, 0.08);
    background: linear-gradient(130deg, #080059, #1c109f);
    color: #fff;
  }
  .hours-modal .modal-title {
    font-size: 1rem;
    font-weight: 700;
  }
  .hours-modal .form-control,
  .hours-modal .form-select {
    border-radius: 10px;
    border: 1px solid rgba(8, 0, 89, 0.16);
    min-height: 42px;
  }
  .hours-modal .form-control:focus,
  .hours-modal .form-select:focus {
    border-color: #eabc73;
    box-shadow: 0 0 0 0.2rem rgba(234, 188, 115, 0.22);
  }
  .date-field-wrap {
    position: relative;
  }
  .date-field-wrap .form-control {
    padding-right: 2.45rem;
    background: #fff;
    cursor: pointer;
  }
  .date-field-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #7d82ad;
    pointer-events: none;
    font-size: 0.95rem;
  }
  .hours-modal .btn-save {
    background: linear-gradient(120deg, #eabc73, #f2d39e);
    color: #080059;
    border: none;
    font-weight: 700;
  }
  .hours-modal.fade .modal-dialog {
    transform: translateY(28px) scale(0.96);
    transition: transform 0.35s ease, opacity 0.35s ease;
  }
  .hours-modal.show .modal-dialog {
    transform: translateY(0) scale(1);
  }
  .hours-modal .select2-container .select2-selection--single,
  .hours-modal .select2-container .select2-selection--multiple {
    border-radius: 10px !important;
    border: 1px solid rgba(8, 0, 89, 0.16) !important;
    min-height: 42px;
  }
  .hours-modal .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 40px;
    padding-left: 0.8rem;
  }
  .hours-modal .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px;
  }
  .hours-modal .select2-container--default .select2-selection--multiple {
    padding: 4px 8px;
  }
  .hours-modal .select2-container--default .select2-selection--multiple .select2-selection__choice {
    background: rgba(234, 188, 115, 0.24);
    border: 1px solid rgba(234, 188, 115, 0.45);
    color: #5a4200;
    border-radius: 999px;
  }
  .hours-error {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.8rem;
    color: #d94b6e;
  }
  .hours-success {
    font-size: 0.85rem;
    color: #1f7a58;
    font-weight: 600;
  }
  .datepicker {
    border: 1px solid rgba(8, 0, 89, 0.14);
    box-shadow: 0 14px 30px rgba(8, 0, 89, 0.2);
    border-radius: 12px;
    padding: 10px;
  }
  .datepicker table tr td,
  .datepicker table tr th {
    border-radius: 8px;
    width: 34px;
    height: 34px;
  }
  .datepicker table tr td.active.active,
  .datepicker table tr td.active:hover.active,
  .datepicker table tr td.active:focus.active {
    background: #080059;
  }
  .datepicker table tr td.today {
    background: rgba(234, 188, 115, 0.32);
    border-color: rgba(234, 188, 115, 0.45);
    color: #4e3a02;
  }
  .datepicker-dropdown.datepicker-orient-bottom::before,
  .datepicker-dropdown.datepicker-orient-bottom::after {
    display: none;
  }
  .datepicker .datepicker-switch,
  .datepicker .prev,
  .datepicker .next {
    color: #080059;
    font-weight: 600;
  }
  .datepicker .month,
  .datepicker .year,
  .datepicker .decade,
  .datepicker .century {
    border-radius: 8px;
  }
  @media (max-width: 575.98px) {
    .hours-modal .modal-dialog {
      margin: 0.65rem;
    }
    .hours-modal .modal-content {
      border-radius: 14px;
    }
    .datepicker {
      width: 100%;
      max-width: 290px;
    }
    .quick-action-btn {
      width: 100%;
      justify-content: center;
    }
  }
</style>
@endsection

@section('content')

    <section class="content-wrap">
      <div class="hero-banner">
        <h2>Manage Your Technical Services Operations Efficiently</h2>
        <p>
          Track projects, optimize field teams, and monitor revenue with a refined real-time operations view.
        </p>
        <div class="d-flex flex-wrap gap-2 mt-3">
          <button type="button" class="btn quick-action-btn primary" id="openWorkingHoursModal">
            <i class="fa-solid fa-clock me-1"></i>Add Working Hours
          </button>
          <button type="button" class="btn quick-action-btn secondary" id="openOvertimeHoursModal">
            <i class="fa-solid fa-business-time me-1"></i>Add Overtime Hours
          </button>
        </div>
      </div>

      <div class="row g-3 mb-2">
        <div class="col-xl-3 col-md-6">
          <div class="card-glass stat-card">
            <div class="stat-icon"><i class="fa-solid fa-diagram-project"></i></div>
            <div class="stat-label">Active Projects</div>
            <div class="stat-value counter" data-target="128">0</div>
            <div class="trend up"><i class="fa-solid fa-arrow-up me-1"></i>+8.4% this month</div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6">
          <div class="card-glass stat-card">
            <div class="stat-icon"><i class="fa-solid fa-clipboard-check"></i></div>
            <div class="stat-label">Pending Jobs</div>
            <div class="stat-value counter" data-target="42">0</div>
            <div class="trend down"><i class="fa-solid fa-arrow-down me-1"></i>-3.1% this week</div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6">
          <div class="card-glass stat-card">
            <div class="stat-icon"><i class="fa-solid fa-sack-dollar"></i></div>
            <div class="stat-label">Revenue (USD)</div>
            <div class="stat-value">$<span class="counter" data-target="276">0</span>K</div>
            <div class="trend up"><i class="fa-solid fa-arrow-up me-1"></i>+12.5% this quarter</div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6">
          <div class="card-glass stat-card">
            <div class="stat-icon"><i class="fa-solid fa-people-group"></i></div>
            <div class="stat-label">Team Members</div>
            <div class="stat-value counter" data-target="34">0</div>
            <div class="trend up"><i class="fa-solid fa-arrow-up me-1"></i>+2 new hires</div>
          </div>
        </div>
      </div>
    </section>
</main>
@endsection

@section('modals')
<div class="modal fade hours-modal" id="workingHoursModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Working Hour</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="workingHoursQuickForm" novalidate>
          <div class="mb-2">
            <label class="form-label">Date</label>
            <div class="date-field-wrap">
              <input type="text" class="form-control friendly-date" id="quickWorkDate" placeholder="Select date" readonly>
              <i class="fa-regular fa-calendar date-field-icon"></i>
            </div>
            <span class="hours-error" id="workingDate-error"></span>
          </div>
          <div class="mb-2">
            <label class="form-label">Project</label>
            <select class="form-select project-select2" id="workingProjectId">
              <option value="">Select project</option>
              @foreach ($projects as $project)
              <option value="{{ $project->id }}">{{ $project->name }}</option>
              @endforeach
            </select>
            <span class="hours-error" id="workingProjectId-error"></span>
          </div>
          <div class="mb-2">
            <label class="form-label">Select Users</label>
            <select class="form-select users-select2" id="workingUserIds" multiple>
              @foreach ($users as $user)
              <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
              @endforeach
            </select>
            <span class="hours-error" id="workingUserIds-error"></span>
          </div>
          <div>
            <label class="form-label">Description</label>
            <textarea class="form-control" rows="3" id="workingDescription" placeholder="Enter description"></textarea>
            <span class="hours-error" id="workingDescription-error"></span>
          </div>
          <span class="hours-success" id="workingSuccessMessage"></span>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">Close</button>
        <button class="btn btn-save" type="button" id="saveWorkingHoursBtn">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade hours-modal" id="overtimeHoursModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Overtime Hour</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="overtimeHoursQuickForm" novalidate>
          <div class="mb-2">
            <label class="form-label">Date</label>
            <div class="date-field-wrap">
              <input type="text" class="form-control friendly-date" id="quickOvertimeDate" placeholder="Select date" readonly>
              <i class="fa-regular fa-calendar date-field-icon"></i>
            </div>
            <span class="hours-error" id="overtimeDate-error"></span>
          </div>
          <div class="mb-2">
            <label class="form-label">Project</label>
            <select class="form-select project-select2" id="overtimeProjectId">
              <option value="">Select project</option>
              @foreach ($projects as $project)
              <option value="{{ $project->id }}">{{ $project->name }}</option>
              @endforeach
            </select>
            <span class="hours-error" id="overtimeProjectId-error"></span>
          </div>
          <div class="mb-2">
            <label class="form-label">Number of Hours Working</label>
            <input type="number" class="form-control" id="overtimeHoursInput" min="0" step="0.25" placeholder="Enter hours">
            <span class="hours-error" id="overtimeHoursInput-error"></span>
          </div>
          <div class="mb-2">
            <label class="form-label">Select Users</label>
            <select class="form-select users-select2" id="overtimeUserIds" multiple>
              @foreach ($users as $user)
              <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
              @endforeach
            </select>
            <span class="hours-error" id="overtimeUserIds-error"></span>
          </div>
          <div>
            <label class="form-label">Description</label>
            <textarea class="form-control" rows="3" id="overtimeDescription" placeholder="Enter description"></textarea>
            <span class="hours-error" id="overtimeDescription-error"></span>
          </div>
          <span class="hours-success" id="overtimeSuccessMessage"></span>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">Close</button>
        <button class="btn btn-save" type="button" id="saveOvertimeHoursBtn">Save</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/js/bootstrap-datepicker.min.js"></script>
<script>
  $(function () {
    const workingHoursModalElement = document.getElementById("workingHoursModal");
    const overtimeHoursModalElement = document.getElementById("overtimeHoursModal");
    const workingHoursModal = new bootstrap.Modal(workingHoursModalElement);
    const overtimeHoursModal = new bootstrap.Modal(overtimeHoursModalElement);

    const getToday = () => new Date();

    function initSelect2(modalElement) {
      $(modalElement).find(".project-select2").select2({
        width: "100%",
        dropdownParent: $(modalElement),
        placeholder: "Select project",
        allowClear: false,
      });

      $(modalElement).find(".users-select2").select2({
        width: "100%",
        dropdownParent: $(modalElement),
        placeholder: "Search and select users",
        closeOnSelect: false,
      });
    }

    initSelect2(workingHoursModalElement);
    initSelect2(overtimeHoursModalElement);

    function clearFormMessages(prefix) {
      $(`#${prefix}Date-error`).text("");
      $(`#${prefix}ProjectId-error`).text("");
      $(`#${prefix}UserIds-error`).text("");
      $(`#${prefix}Description-error`).text("");
      $(`#${prefix}SuccessMessage`).text("");
    }

    function setWorkingErrors(errors = {}) {
      if (errors.project_id) $("#workingProjectId-error").text(errors.project_id[0]);
      if (errors.user_ids) $("#workingUserIds-error").text(errors.user_ids[0]);
      if (errors.description) $("#workingDescription-error").text(errors.description[0]);
      if (errors.date) $("#workingDate-error").text(errors.date[0]);
    }

    function setOvertimeErrors(errors = {}) {
      if (errors.project_id) $("#overtimeProjectId-error").text(errors.project_id[0]);
      if (errors.user_ids) $("#overtimeUserIds-error").text(errors.user_ids[0]);
      if (errors.description) $("#overtimeDescription-error").text(errors.description[0]);
      if (errors.hours) $("#overtimeHoursInput-error").text(errors.hours[0]);
      if (errors.date) $("#overtimeDate-error").text(errors.date[0]);
    }

    function resetWorkingForm() {
      $("#workingHoursQuickForm")[0].reset();
      $("#workingProjectId").val("").trigger("change");
      $("#workingUserIds").val(null).trigger("change");
      $("#quickWorkDate").datepicker("setDate", getToday());
      clearFormMessages("working");
    }

    function resetOvertimeForm() {
      $("#overtimeHoursQuickForm")[0].reset();
      $("#overtimeProjectId").val("").trigger("change");
      $("#overtimeUserIds").val(null).trigger("change");
      $("#quickOvertimeDate").datepicker("setDate", getToday());
      $("#overtimeHoursInput-error").text("");
      clearFormMessages("overtime");
    }

    $(".friendly-date").each(function () {
      $(this).datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        todayHighlight: true,
        orientation: "bottom auto",
        container: "body",
      }).datepicker("setDate", getToday());
    });

    $("#openWorkingHoursModal").on("click", function () {
      resetWorkingForm();
      workingHoursModal.show();
    });

    $("#openOvertimeHoursModal").on("click", function () {
      resetOvertimeForm();
      overtimeHoursModal.show();
    });

    $("#saveWorkingHoursBtn").on("click", function () {
      const btn = $(this);
      clearFormMessages("working");

      btn.prop("disabled", true).html('<i class="fa-solid fa-spinner fa-spin"></i> Saving...');

      $.ajax({
        url: "{{ route('dashboard.working-hours.store') }}",
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}",
          project_id: $("#workingProjectId").val(),
          user_ids: $("#workingUserIds").val() || [],
          date: $("#quickWorkDate").val(),
          description: $("#workingDescription").val(),
        },
        success: function (response) {
          $("#workingSuccessMessage").text(response.message);
          resetWorkingForm();
          $("#workingSuccessMessage").text(response.message);
        },
        error: function (xhr) {
          if (xhr.responseJSON?.errors) {
            setWorkingErrors(xhr.responseJSON.errors);
          } else if (xhr.responseJSON?.message) {
            $("#workingUserIds-error").text(xhr.responseJSON.message);
          }
        },
        complete: function () {
          btn.prop("disabled", false).text("Save");
        }
      });
    });

    $("#saveOvertimeHoursBtn").on("click", function () {
      const btn = $(this);
      clearFormMessages("overtime");
      $("#overtimeHoursInput-error").text("");

      btn.prop("disabled", true).html('<i class="fa-solid fa-spinner fa-spin"></i> Saving...');

      $.ajax({
        url: "{{ route('dashboard.overtime-hours.store') }}",
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}",
          project_id: $("#overtimeProjectId").val(),
          user_ids: $("#overtimeUserIds").val() || [],
          date: $("#quickOvertimeDate").val(),
          hours: $("#overtimeHoursInput").val(),
          description: $("#overtimeDescription").val(),
        },
        success: function (response) {
          $("#overtimeSuccessMessage").text(response.message);
          resetOvertimeForm();
          $("#overtimeSuccessMessage").text(response.message);
        },
        error: function (xhr) {
          if (xhr.responseJSON?.errors) {
            setOvertimeErrors(xhr.responseJSON.errors);
          } else if (xhr.responseJSON?.message) {
            $("#overtimeUserIds-error").text(xhr.responseJSON.message);
          }
        },
        complete: function () {
          btn.prop("disabled", false).text("Save");
        }
      });
    });
  });
</script>
@endsection