@extends('backend.app')

@section('title', 'Subscription Plans')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            {{-- Start page title --}}
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('subscription-plan.index') }}">Table</a></li>
                                <li class="breadcrumb-item active">Subscription Plans</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End page title --}}

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">All Subscription Plan List</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable"
                                    class="table table-bordered table-striped align-middle dt-responsive nowrap"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="column-id">#</th>
                                            <th class="column-content">Subscription Plan</th>
                                            <th class="column-content">Price</th>
                                            <th class="column-content">Billing Cycle</th>
                                            <th class="column-content">Touch Points Limit</th>
                                            <th class="column-content">Has Ads</th>
                                            <th class="column-status">Status</th>
                                            <th class="column-action">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Dynamic Data --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editServiceModal" tabindex="-1" aria-labelledby="editServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editServiceModalLabel">Edit Subscription Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editServiceForm">
                    @csrf
                    @method('PUT')

                    <input type="hidden" id="edit_plan_id" name="id">

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_subscription_plan" class="form-label">Subscription Plan</label>
                            <select class="form-control" id="edit_subscription_plan" name="subscription_plan" disabled>
                                <option value="free">Free</option>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                                <option value="lifetime">Lifetime</option>
                            </select>
                            <small class="text-danger edit_subscription_plan_error"></small>
                        </div>

                        <div class="mb-3">
                            <label for="edit_price" class="form-label">Price</label>
                            <input type="number" step="0.01" class="form-control" id="edit_price" name="price">
                            <small class="text-danger edit_price_error"></small>
                        </div>

                        <div class="mb-3">
                            <label for="edit_billing_cycle" class="form-label">Billing Cycle</label>
                            <select class="form-control" id="edit_billing_cycle" name="billing_cycle">
                                <option value="monthly">monthly</option>
                                <option value="yearly">yearly</option>
                                <option value="lifetime">lifetime</option>
                            </select>
                            <small class="text-danger edit_billing_cycle_error"></small>
                        </div>

                        <div class="mb-3">
                            <label for="edit_touch_points" class="form-label">Touch Points</label>
                            <input type="number" class="form-control" id="edit_touch_points" name="touch_points"
                                placeholder="Set it Empty for Unlimited">
                            <small class="text-danger edit_touch_points_error"></small>
                        </div>

                        <div class="mb-3">
                            <label for="edit_has_ads" class="form-label">Has Ads?</label>
                            <select class="form-control" id="edit_has_ads" name="has_ads">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                            <small class="text-danger edit_has_ads_error"></small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Plan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- For index table --}}
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            var table = $('#datatable').DataTable({
                responsive: true,
                order: [],
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"],
                ],
                processing: true,
                serverSide: true,
                pagingType: "full_numbers",
                ajax: {
                    url: "{{ route('subscription-plan.index') }}",
                    type: "GET",
                },
                dom: "<'row table-topbar'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>>" +
                    "<'row'<'col-12'tr>>" +
                    "<'row table-bottom'<'col-md-5 dataTables_left'i><'col-md-7'p>>",
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records...",
                    lengthMenu: "Show _MENU_ entries",
                    processing: `
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>`,
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'subscription_plan',
                        name: 'subscription_plan',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'price',
                        name: 'price',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'billing_cycle',
                        name: 'billing_cycle',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'touch_points',
                        name: 'touch_points',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'has_ads',
                        name: 'has_ads',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                ],
                columnDefs: [{
                    targets: -1,
                    render: function(data, type, row) {
                        return `
                            <div class="hstack gap-3 fs-base">
                                <a href="javascript:void(0);" class="link-primary text-decoration-none edit-service" data-id="${row.id}" title="Edit">
                                    <i class="ri-pencil-line" style="font-size: 34px;"></i>
                                </a>
                            </div>
                        `;
                    },
                }],
            });


            // Show Edit Modal (populate directly from DataTable row data)
            $(document).on('click', '.edit-service', function() {
                let tr = $(this).closest('tr');
                let rowData = table.row(tr).data();

                // Populate all fields
                $('#edit_plan_id').val(rowData.id);
                $('#edit_subscription_plan').val(rowData.subscription_plan);
                $('#edit_price').val(rowData.price);
                $('#edit_billing_cycle').val(rowData.billing_cycle);
                $('#edit_touch_points').val(rowData.touch_points === 'Unlimited' ? '' : rowData
                    .touch_points);
                $('#edit_has_ads').val(rowData.has_ads ? '1' : '0');
                $('#edit_icon').val(rowData.icon ? '1' : '0');

                // Remove any old hidden helpers
                $('.free-hidden').remove();

                if (rowData.subscription_plan === 'free') {
                    // Disable the four fields
                    $('#edit_price, #edit_billing_cycle, #edit_has_ads, #edit_icon')
                        .prop('disabled', true);

                    // And still submit their values via hidden inputs:
                    $('#editServiceForm')
                        .append(
                            `<input type="hidden" class="free-hidden" name="price" value="${rowData.price}">`
                        )
                        .append(
                            `<input type="hidden" class="free-hidden" name="billing_cycle" value="${rowData.billing_cycle}">`
                        )
                        .append(
                            `<input type="hidden" class="free-hidden" name="has_ads" value="${rowData.has_ads ? 1 : 0}">`
                        )
                        .append(
                            `<input type="hidden" class="free-hidden" name="icon" value="${rowData.icon ? 1 : 0}">`
                        );
                } else {
                    // Re-enable for paid plans
                    $('#edit_price, #edit_billing_cycle, #edit_has_ads, #edit_icon')
                        .prop('disabled', false);
                }

                // Clear previous errors
                $('#editServiceForm .text-danger').text('');

                // Show it!
                $('#editServiceModal').modal('show');
            });

            // Handle Edit Form Submission
            $('#editServiceForm').submit(function(e) {
                e.preventDefault();
                $('.error-text').text('');

                let serviceId = $('#edit_plan_id').val();
                let formData = $(this).serialize();

                let updateUrlTemplate = '{{ route('subscription-plan.update', ['id' => ':id']) }}';
                axios.put(
                        updateUrlTemplate.replace(':id', serviceId),
                        formData
                    )
                    .then(function(response) {
                        if (response.data.success) {
                            $('#editServiceModal').modal('hide');
                            $('#editServiceForm')[0].reset();
                            table.ajax.reload();
                            toastr.success(response.data.message);
                        } else {
                            // Validation errors
                            $.each(response.data.errors, function(key, value) {
                                $('.edit_' + key + '_error').text(value[0]);
                            });
                            toastr.error('Please fix the errors.');
                        }
                    })
                    .catch(function(error) {
                        console.error('Error updating plan:', error);
                        toastr.error('An error occurred while updating the plan.');
                    });
            });
        });
    </script>

    {{-- For status change --}}
    <script>
        function showStatusChangeAlert(id) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to update the status?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    statusChange(id);
                }
            });
        }

        function statusChange(id) {
            let url = '{{ route('subscription-plan.status', ['id' => ':id']) }}'.replace(':id', id);
            $.ajax({
                type: "GET",
                url: url,
                success: function(resp) {
                    console.log(resp);
                    $('#datatable').DataTable().ajax.reload();
                    if (resp.success === true) {
                        toastr.success(resp.message);
                    } else if (resp.errors) {
                        toastr.error(resp.errors[0]);
                    } else {
                        toastr.error(resp.message);
                    }
                },
                error: function(error) {
                    toastr.error('An error occurred. Please try again.');
                }
            });
        }
    </script>
@endpush
