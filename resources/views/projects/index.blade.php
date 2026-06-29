@extends('layouts.dashboard')

@section('title', 'Projects')

@section('style')
<link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<style>
  .projects-content { padding: 1.3rem; }
  .projects-card {
    border-radius: 16px;
    border: 1px solid rgba(8, 0, 89, 0.08);
    background: rgba(255, 255, 255, 0.72);
    backdrop-filter: blur(10px);
    box-shadow: 0 12px 30px rgba(8, 0, 89, 0.08);
    padding: 1rem;
  }
  .projects-title { color: #080059; font-weight: 700; margin: 0; }
  .projects-subtitle { color: #6f7294; margin: 0; font-size: 0.9rem; }
  .btn-add-project {
    border: none;
    border-radius: 12px;
    background: linear-gradient(120deg, #eabc73, #f1d19a);
    color: #080059;
    font-weight: 700;
    padding: 0.62rem 0.95rem;
    box-shadow: 0 10px 22px rgba(234, 188, 115, 0.35);
  }
  .table-chip { border-radius: 999px; padding: 0.33rem 0.62rem; font-size: 0.75rem; font-weight: 600; display: inline-block; }
  .status-planned { background: rgba(39, 130, 255, 0.14); color: #2d66ba; }
  .status-in-progress { background: rgba(234, 188, 115, 0.24); color: #7a5500; }
  .status-completed { background: rgba(28, 166, 115, 0.16); color: #1f7a58; }
  .status-on-hold { background: rgba(220, 76, 100, 0.14); color: #b13a50; }
  .action-btn {
    width: 34px; height: 34px; border: 1px solid rgba(8, 0, 89, 0.16); border-radius: 10px;
    background: #fff; color: #080059; transition: all 0.25s ease; margin-right: 0.3rem;
  }
  .action-btn.delete-project:hover { color: #d94b6e; border-color: #d94b6e; }
  .projects-modal .modal-content { border-radius: 16px; border: 1px solid rgba(8, 0, 89, 0.09); overflow: hidden; }
  .projects-modal .modal-header { border-bottom: 1px solid rgba(8, 0, 89, 0.08); background: linear-gradient(130deg, #080059, #1c109f); color: #fff; }
  .projects-modal .form-control, .projects-modal .form-select { border-radius: 10px; border: 1px solid rgba(8, 0, 89, 0.16); min-height: 42px; }
  .projects-modal .btn-save { background: linear-gradient(120deg, #eabc73, #f2d39e); color: #080059; border: none; font-weight: 700; }
  .error-message { font-size: 0.8rem; display: block; margin-top: 0.2rem; }
</style>
@endsection

@section('content')
<section class="projects-content">
  <div class="projects-card">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
      <div>
        <h5 class="projects-title">Project Management</h5>
        <p class="projects-subtitle">Manage project records, timelines, and budgets</p>
      </div>
      <button class="btn btn-add-project" id="openAddProject">
        <i class="fa-solid fa-plus me-1"></i>Add Project
      </button>
    </div>

    <div class="table-responsive">
      <table id="projectsTable" class="table align-middle mb-0">
        <thead>
          <tr>
            <th>Name</th>
            <th>Location</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Budget</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($projects as $project)
          <tr data-project-id="{{ $project->id }}" data-project-description="{{ $project->description }}">
            <td>{{ $project->name }}</td>
            <td>{{ $project->location }}</td>
            <td>{{ $project->start_date }}</td>
            <td>{{ $project->end_date }}</td>
            <td>{{ number_format($project->budget, 2) }}</td>
            <td><span class="table-chip status-{{ str_replace(' ', '-', strtolower($project->status)) }}">{{ $project->status }}</span></td>
            <td>
              <button class="action-btn edit-project" title="Edit"><i class="fa-regular fa-pen-to-square"></i></button>
              <button class="action-btn delete-project" title="Delete"><i class="fa-regular fa-trash-can"></i></button>
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
<div class="modal fade projects-modal" id="projectModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="projectModalTitle">Add Project</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="projectForm" novalidate>
          <input type="hidden" id="editingProjectId">
          <div class="mb-2">
            <label class="form-label">Project Name</label>
            <input type="text" class="form-control" id="projectName">
            <span class="text-danger error-message" id="projectName-error"></span>
          </div>
          <div class="mb-2">
            <label class="form-label">Description</label>
            <input type="text" class="form-control" id="projectDescription">
            <span class="text-danger error-message" id="projectDescription-error"></span>
          </div>
          <div class="mb-2">
            <label class="form-label">Location</label>
            <input type="text" class="form-control" id="projectLocation">
            <span class="text-danger error-message" id="projectLocation-error"></span>
          </div>
          <div class="row g-2">
            <div class="col-sm-6">
              <label class="form-label">Start Date</label>
              <input type="date" class="form-control" id="projectStartDate">
              <span class="text-danger error-message" id="projectStartDate-error"></span>
            </div>
            <div class="col-sm-6">
              <label class="form-label">End Date</label>
              <input type="date" class="form-control" id="projectEndDate">
              <span class="text-danger error-message" id="projectEndDate-error"></span>
            </div>
          </div>
          <div class="row g-2 mt-1">
            <div class="col-sm-6">
              <label class="form-label">Budget</label>
              <input type="number" min="0" step="0.01" class="form-control" id="projectBudget">
              <span class="text-danger error-message" id="projectBudget-error"></span>
            </div>
            <div class="col-sm-6">
              <label class="form-label">Status</label>
              <select class="form-select" id="projectStatus">
                <option value="">Select status</option>
                <option value="Planned">Planned</option>
                <option value="In Progress">In Progress</option>
                <option value="Completed">Completed</option>
                <option value="On Hold">On Hold</option>
              </select>
              <span class="text-danger error-message" id="projectStatus-error"></span>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
        <button class="btn btn-save" id="saveProjectBtn" type="button">Save Project</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade projects-modal" id="deleteProjectModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Deletion</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="mb-0">Are you sure you want to delete <strong id="deleteProjectName">this project</strong>?</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
        <button class="btn btn-danger" id="confirmDeleteProjectBtn" type="button">Delete Project</button>
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
    const projectModal = new bootstrap.Modal(document.getElementById("projectModal"));
    const deleteProjectModal = new bootstrap.Modal(document.getElementById("deleteProjectModal"));
    const table = $("#projectsTable").DataTable();
    let pendingDeleteProjectId = null;
    let pendingDeleteRowNode = null;

    function chipClass(status) {
      return `status-${String(status).toLowerCase().replaceAll(" ", "-")}`;
    }

    function buildStatusChip(status) {
      return `<span class="table-chip ${chipClass(status)}">${status}</span>`;
    }

    function buildActionButtons() {
      return `
        <button class="action-btn edit-project" title="Edit"><i class="fa-regular fa-pen-to-square"></i></button>
        <button class="action-btn delete-project" title="Delete"><i class="fa-regular fa-trash-can"></i></button>
      `;
    }

    function resetForm() {
      $("#projectForm")[0].reset();
      $(".error-message").html("");
      $("#editingProjectId").val("");
      $("#projectModalTitle").text("Add Project");
    }

    function cellText(value) {
      if (typeof value !== "string") return value ?? "";
      return $("<div>").html(value).text().trim();
    }

    function payload() {
      return {
        _token: '{{ csrf_token() }}',
        name: $("#projectName").val(),
        description: $("#projectDescription").val(),
        location: $("#projectLocation").val(),
        start_date: $("#projectStartDate").val(),
        end_date: $("#projectEndDate").val(),
        budget: $("#projectBudget").val(),
        status: $("#projectStatus").val(),
      };
    }

    function setErrors(errors = {}) {
      if (errors.name) $("#projectName-error").text(errors.name[0]);
      if (errors.description) $("#projectDescription-error").text(errors.description[0]);
      if (errors.location) $("#projectLocation-error").text(errors.location[0]);
      if (errors.start_date) $("#projectStartDate-error").text(errors.start_date[0]);
      if (errors.end_date) $("#projectEndDate-error").text(errors.end_date[0]);
      if (errors.budget) $("#projectBudget-error").text(errors.budget[0]);
      if (errors.status) $("#projectStatus-error").text(errors.status[0]);
    }

    $("#openAddProject").on("click", function () {
      resetForm();
      projectModal.show();
    });

    $("#saveProjectBtn").on("click", function () {
      const btn = $(this);
      const projectId = $("#editingProjectId").val();
      const isEdit = !!projectId;

      btn.prop("disabled", true).html('<i class="fa-solid fa-spinner fa-spin"></i> Saving...');
      $(".error-message").html("");

      $.ajax({
        url: isEdit ? `{{ url('/dashboard/projects') }}/${projectId}` : "{{ route('dashboard.projects.store') }}",
        type: isEdit ? "PUT" : "POST",
        data: payload(),
        success: function (response) {
          const project = response.data;
          const rowData = [
            project.name,
            project.location ?? "",
            project.start_date,
            project.end_date,
            Number(project.budget).toFixed(2),
            buildStatusChip(project.status),
            buildActionButtons(),
          ];

          if (isEdit) {
            const rowNode = $(`#projectsTable tbody tr[data-project-id="${project.id}"]`);
            const row = table.row(rowNode);
            row.data(rowData).draw(false);
            $(row.node()).attr("data-project-id", project.id);
            $(row.node()).attr("data-project-description", project.description ?? "");
          } else {
            const newRow = table.row.add(rowData).draw(false).node();
            $(newRow).attr("data-project-id", project.id);
            $(newRow).attr("data-project-description", project.description ?? "");
          }

          projectModal.hide();
        },
        error: function (xhr) {
          if (xhr.responseJSON?.errors) {
            setErrors(xhr.responseJSON.errors);
          }
        },
        complete: function () {
          btn.prop("disabled", false).html("Save Project");
        },
      });
    });

    $("#projectsTable tbody").on("click", ".edit-project", function () {
      const rowNode = $(this).closest("tr");
      const projectId = rowNode.data("project-id");
      const row = table.row(rowNode);
      const data = row.data();

      $("#projectName").val(cellText(data[0]));
      $("#projectLocation").val(cellText(data[1]));
      $("#projectStartDate").val(cellText(data[2]));
      $("#projectEndDate").val(cellText(data[3]));
      $("#projectBudget").val(cellText(data[4]).replace(/,/g, ""));
      $("#projectStatus").val(cellText(data[5]));
      $("#projectDescription").val(rowNode.attr("data-project-description") ?? "");
      $("#editingProjectId").val(projectId);
      $("#projectModalTitle").text("Edit Project");
      $(".error-message").html("");
      projectModal.show();
    });

    $("#projectsTable tbody").on("click", ".delete-project", function () {
      const rowNode = $(this).closest("tr");
      const projectId = rowNode.data("project-id");
      const row = table.row(rowNode);
      const data = row.data();

      if (!projectId) return;

      pendingDeleteProjectId = projectId;
      pendingDeleteRowNode = rowNode;
      $("#deleteProjectName").text(cellText(data[0]) || "this project");
      deleteProjectModal.show();
    });

    $("#confirmDeleteProjectBtn").on("click", function () {
      if (!pendingDeleteProjectId || !pendingDeleteRowNode) return;

      const btn = $(this);
      btn.prop("disabled", true).html('<i class="fa-solid fa-spinner fa-spin"></i> Deleting...');

      $.ajax({
        url: `{{ url('/dashboard/projects') }}/${pendingDeleteProjectId}`,
        type: "DELETE",
        data: { _token: '{{ csrf_token() }}' },
        success: function () {
          table.row(pendingDeleteRowNode).remove().draw(false);
          deleteProjectModal.hide();
        },
        complete: function () {
          btn.prop("disabled", false).html("Delete Project");
          pendingDeleteProjectId = null;
          pendingDeleteRowNode = null;
        },
      });
    });

    $("#projectModal").on("hidden.bs.modal", function () {
      resetForm();
    });
  });
</script>
@endsection
