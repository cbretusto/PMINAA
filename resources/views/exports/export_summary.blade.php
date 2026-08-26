@php
    session_start();
    $layout = 'layouts.no_access';
    $invoiceUserDepartment = '';
    if(isset($_SESSION['invoice_revision_request_id'])){
        $layout = 'layouts.layout';
        $invoiceUserDepartment = $_SESSION['invoice_user_department'];
    }

    if (isset($_SESSION['rapidx_user_id'])){
        $sessionCheck = $_SESSION['rapidx_user_id'];
    }else{
        $sessionCheck = '';
    }
@endphp
@extends($layout)
@section('title', 'Export Report')
@section('content_page')
    <div class="content-wrapper layout-fixed">
        <section class="content p-3">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <span class="card-title"><strong>Export Report</strong></span>
                            </div>
                            <div class="card-body">
                                @if(session()->has('message'))
                                    <div class="alert alert-danger">
                                        <strong>{{ session()->get('message') }}</strong>
                                    </div>
                                @endif
                                <div class="input-group mb-3">
                                    <span class="input-group-text w-25"><strong>Department:</strong></span> 
                                    <select class="form-select" id="slctExportDepartment" name="export_department">
                                        <option disabled selected value=""> ----- </option>
                                        <option value="CN">CN</option> 
                                        <option value="TS">TS</option> 
                                        <option value="PPD">PPD</option> 
                                        <option value="YF">YF</option> 
                                    </select>    
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text w-25"><strong>Invoice Category:</strong></span> 
                                    <select class="form-select" id="slctExportCategory" name="export_category">
                                        <option disabled selected value=""> ----- </option>
                                        <option value="1">FGS</option> 
                                        <option value="2">Raw Material</option> 
                                    </select>    
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text w-25"><strong>Revised Date From:</strong></span> 
                                    <input type="date" class="form-control" id="txtExportRevisedDateFrom" name="from" max="<?= date('Y-m-d'); ?>">
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text w-25"><strong>Revised Date To:</strong></span> 
                                    <input type="date" class="form-control" id="txtExportRevisedDateTo" name="to" max="<?= date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-dark float-end" id="btnExportInvoiceRevisionRequest"><i class="fas fa-file-excel"></i> Export</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

<!-- JS CONTENT --}} -->
@section('js_content')
    <script type="text/javascript">
        $(document).ready(function () {      
            $('#btnExportInvoiceRevisionRequest').on('click', function(){
                let department  = $('#slctExportDepartment').val();
                let category    = $('#slctExportCategory').val();
                let from        = $('#txtExportRevisedDateFrom').val();
                let to          = $('#txtExportRevisedDateTo').val();
                console.log('category: ', category);
                if(department == null){
                    console.log('department',department)
                    alert('Select department');
                }else if(category == null){
                    console.log('category',category)
                    alert('Select Category');
                }else if(from == ''){
                    console.log('from',from)
                    alert('Select Date From');
                }else if(to == ''){
                    console.log('to',to)
                    alert('Select Date To');
                }else{
                    window.location.href = `export/${department}/${category}/${from}/${to}`;
                    $('.alert').remove();
                }            
            });
        });
    </script>
@endsection
