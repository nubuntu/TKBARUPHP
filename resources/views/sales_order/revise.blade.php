@extends('layouts.adminlte.master')

@section('title')
    @lang('sales_order.revise.title')
@endsection

@section('page_title')
    <span class="fa fa-code-fork fa-fw"></span>&nbsp;@lang('sales_order.revise.page_title')
@endsection

@section('page_title_desc')
    @lang('sales_order.revise.page_title_desc')
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('revise_sales_order_detail', $currentSo) !!}
@endsection

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>@lang('labels.GENERAL_ERROR_TITLE')</strong> @lang('labels.GENERAL_ERROR_DESC')<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div id="soVue">
        {!! Form::model($currentSo, ['method' => 'PATCH', 'route' => ['db.so.revise', $currentSo->hId()], 'class' => 'form-horizontal', 'data-parsley-validate' => 'parsley']) !!}
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-6">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('sales_order.revise.box.customer')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="inputCustomerType"
                                       class="col-sm-2 control-label">@lang('sales_order.revise.field.customer_type')</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" readonly
                                           value="@lang('lookup.'.$currentSo->customer_type)">
                                </div>
                            </div>
                            @if($currentSo->customer_type == 'CUSTOMERTYPE.R')
                                <div class="form-group">
                                    <label for="inputCustomerId"
                                           class="col-sm-2 control-label">@lang('sales_order.revise.field.customer_name')</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" readonly value="{{ $currentSo->customer->name }}">
                                    </div>
                                    <div class="col-sm-2">
                                        <button id="customerDetailButton" type="button"
                                                class="btn btn-primary btn-sm"
                                                data-toggle="modal" data-target="#customerDetailModal">
                                            <span class="fa fa-info-circle fa-lg"></span>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="form-group">
                                    <label for="inputCustomerName"
                                           class="col-sm-2 control-label">@lang('sales_order.revise.field.customer_name')</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="{{ $currentSo->walk_in_cust }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputCustomerDetails"
                                           class="col-sm-2 control-label">@lang('sales_order.revise.field.customer_details')</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" rows="5" readonly>{{ $currentSo->walk_in_cust_detail }}</textarea>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('sales_order.revise.box.sales_order_detail')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="inputSoCode"
                                       class="col-sm-3 control-label">@lang('sales_order.revise.so_code')</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" readonly value="{{ $currentSo->code }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputSoType"
                                       class="col-sm-3 control-label">@lang('sales_order.revise.so_type')</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" readonly
                                           value="@lang('lookup.'.$currentSo->so_type)">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputSoDate"
                                       class="col-sm-3 control-label">@lang('sales_order.revise.so_date')</label>
                                <div class="col-sm-9">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control" readonly
                                               value="{{ $currentSo->so_created->format('d-m-Y') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputSoStatus"
                                       class="col-sm-3 control-label">@lang('sales_order.revise.so_status')</label>
                                <div class="col-sm-9">
                                    <label class="control-label control-label-normal">@lang('lookup.'.$currentSo->status)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('sales_order.revise.box.shipping')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="inputShippingDate"
                                       class="col-sm-3 control-label">@lang('sales_order.revise.field.shipping_date')</label>
                                <div class="col-sm-9">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        @if($currentSo->status == 'SOSTATUS.WD')
                                            <input type="text" class="form-control" id="inputShippingDate"
                                                   name="shipping_date"
                                                   value="{{ $currentSo->shipping_date->format('d-m-Y') }}"
                                                   data-parsley-required="true">
                                        @else
                                            <input type="text" class="form-control" readonly name="shipping_date"
                                                   value="{{ $currentSo->shipping_date->format('d-m-Y') }}"
                                                   data-parsley-required="true">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputWarehouse"
                                       class="col-sm-3 control-label">@lang('sales_order.revise.field.warehouse')</label>
                                <div class="col-sm-9">
                                    @if($currentSo->status == 'SOSTATUS.WD')
                                        <input type="hidden" name="warehouse_id" v-bind:value="so.warehouse.id">
                                        <input type="hidden" name="warehouse_name" v-bind:value="so.warehouse.name">
                                        <select id="inputWarehouse" data-parsley-required="true"
                                                class="form-control"
                                                v-model="so.warehouse">
                                            <option v-bind:value="{id: ''}">@lang('labels.PLEASE_SELECT')</option>
                                            <option v-for="warehouse in warehouseDDL" v-bind:value="warehouse">@{{ warehouse.name }}</option>
                                        </select>
                                    @else
                                        <input type="text" class="form-control" readonly
                                               value="{{ $currentSo->warehouse->name }}">
                                        <input type="hidden" name="warehouse_id"
                                               value="{{ $currentSo->warehouse->id }}">
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputVendorTrucking"
                                       class="col-sm-3 control-label">@lang('sales_order.revise.field.vendor_trucking')</label>
                                <div class="col-sm-9">
                                    @if($currentSo->status == 'SOSTATUS.WD')
                                        <input type="hidden" name="vendor_trucking_id" v-bind:value="so.vendorTrucking.id">
                                        <input type="hidden" name="vendor_trucking_name" v-bind:value="so.vendorTrucking.name">
                                        <select id="inputVendorTrucking"
                                                class="form-control"
                                                v-model="so.vendorTrucking">
                                            <option v-bind:value="{id: 0, name: ''}">@lang('labels.PLEASE_SELECT')</option>
                                            <option v-for="vendorTrucking in vendorTruckingDDL" v-bind:value="vendorTrucking">@{{ vendorTrucking.name }}</option>
                                        </select>
                                    @else
                                        <input type="text" class="form-control" readonly
                                               value="{{ empty($currentSo->vendorTrucking->name) ? '':$currentSo->vendorTrucking->name }}">
                                        <input type="hidden" name="vendor_trucking_id"
                                               value="{{ empty($currentSo->vendorTrucking->id) ? '':$currentSo->vendorTrucking->id }}">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('sales_order.revise.box.transactions')</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                @if($currentSo->status == 'SOSTATUS.WD')
                                    @if($currentSo->so_type == 'SOTYPE.SVC')
                                        <div class="col-md-11">
                                            <select id="inputProduct"
                                                    class="form-control"
                                                    v-model="so.product">
                                                <option v-bind:value="{id: ''}">@lang('labels.PLEASE_SELECT')</option>
                                                <option v-for="product in productDDL" v-bind:value="product">@{{ product.name }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-primary btn-md"
                                                    v-on:click="insertProduct(so.product)"><span class="fa fa-plus"/>
                                            </button>
                                        </div>
                                    @else
                                        <div class="col-md-11">
                                            <select id="inputStock"
                                                    class="form-control"
                                                    v-model="so.stock">
                                                <option v-bind:value="{id: ''}">@lang('labels.PLEASE_SELECT')</option>
                                                <option v-for="stock in stocksDDL" v-bind:value="stock">@{{ stock.product.name }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-primary btn-md"
                                                    v-on:click="insertStock(so.stock)"><span class="fa fa-plus"/>
                                            </button>
                                        </div>
                                    @endif
                                @endif
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="itemsListTable" class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th width="30%">@lang('sales_order.revise.table.item.header.product_name')</th>
                                            <th width="15%">@lang('sales_order.revise.table.item.header.quantity')</th>
                                            <th width="15%"
                                                class="text-right">@lang('sales_order.revise.table.item.header.unit')</th>
                                            <th width="15%"
                                                class="text-right">@lang('sales_order.revise.table.item.header.price_unit')</th>
                                            <th width="5%">&nbsp;</th>
                                            <th width="20%"
                                                class="text-right">@lang('sales_order.revise.table.item.header.total_price')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(item, itemIndex) in so.items">
                                            <input type="hidden" name="item_id[]" v-bind:value="item.id">
                                            <input type="hidden" name="product_id[]" v-bind:value="item.product.id">
                                            <input type="hidden" name="stock_id[]" v-bind:value="item.stock.id">
                                            <input type="hidden" name="base_unit_id[]" v-bind:value="item.base_unit.unit.id">
                                            <td class="valign-middle">@{{ item.product.name }}</td>
                                            <td>
                                                <input type="text" class="form-control text-right" name="quantity[]"
                                                       v-model="item.quantity" data-parsley-required="true"
                                                       data-parsley-type="number" {{ $currentSo->status == 'SOSTATUS.WD' ? '' : 'readonly' }}>
                                            </td>
                                            <td>
                                                @if($currentSo->status == 'SOSTATUS.WD')
                                                    <input type="hidden" name="selected_unit_id[]" v-bind:value="item.selected_unit.unit.id">
                                                    <select class="form-control"
                                                            v-model="item.selected_unit"
                                                            data-parsley-required="true">
                                                        <option v-bind:value="{unit: {id: '', conversion_value: 1}}">@lang('labels.PLEASE_SELECT')</option>
                                                        <option v-for="product_unit in item.product.product_units" v-bind:value="product_unit">@{{ product_unit.unit.name + ' (' + product_unit.unit.symbol + ')' }}</option>
                                                    </select>
                                                @else
                                                    <input type="text" class="form-control" readonly
                                                           v-bind:value="item.selected_unit.unit.name + ' (' + item.selected_unit.unit.symbol + ')'">
                                                    <input type="hidden" name="selected_unit_id[]"
                                                           v-bind:value="item.selected_unit.unit.id">
                                                @endif
                                            </td>
                                            <td>
                                                <input type="text" class="form-control text-right" name="price[]"
                                                       v-model="item.price" data-parsley-required="true" autonumeric>
                                            </td>
                                            <td class="text-center">
                                                @if($currentSo->status == 'SOSTATUS.WD')
                                                    <button type="button" class="btn btn-danger btn-md"
                                                            v-on:click="removeItem(itemIndex)"><span
                                                                class="fa fa-minus"/>
                                                    </button>
                                                @endif
                                            </td>
                                            <td class="text-right valign-middle">
                                                @{{ item.selected_unit.conversion_value * item.quantity * item.price }}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="itemsTotalListTable" class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <td width="80%"
                                                class="text-right">@lang('sales_order.revise.table.total.body.total')</td>
                                            <td width="20%" class="text-right">
                                                <span class="control-label-normal">@{{ grandTotal() }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('purchase_order.create.box.discount_per_item')</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="discountsListTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th width="30%">@lang('purchase_order.create.table.item.header.product_name')</th>
                                                <th width="30%">@lang('purchase_order.create.table.item.header.total_price')</th>
                                                <th width="40%" class="text-left" colspan="3">@lang('purchase_order.create.table.item.header.total_price')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template v-for="(item, itemIndex) in so.items">
                                                <tr>
                                                    <td width="30%">@{{ item.product.name }}</td>
                                                    <td width="30%">@{{ item.selected_unit.conversion_value * item.quantity * item.price }}</td>
                                                    <td colspan="3" width="40%">
                                                        <button type="button" class="btn btn-primary btn-xs pull-right" v-on:click="insertDiscount(item)">
                                                            <span class="fa fa-plus"/>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" width="65%" ></td>
                                                    <th width="10%" class="small-header">@lang('purchase_order.create.table.item.header.discount_percent')</th>
                                                    <th width="25%" class="small-header">@lang('purchase_order.create.table.item.header.discount_nominal')</th>
                                                </tr>
                                                <tr v-for="(discount, discountIndex) in item.discounts">
                                                    <td colspan="2" width="60%"></td>
                                                    <td class="text-center valign-middle" width="5%">
                                                        <button type="button" class="btn btn-danger btn-md" v-on:click="removeDiscount(itemIndex, discountIndex)">
                                                                <span class="fa fa-minus"></span>
                                                        </button>
                                                    </td>
                                                    <td width="10%">
                                                        <input type="text" class="form-control text-right" v-bind:name="'item_disc_percent['+itemIndex+'][]'" v-model="discount.disc_percent" placeholder="%" v-on:keyup="discountPercentToNominal(item, discount)" />
                                                    </td>
                                                    <td width="25%">
                                                        <input type="text" class="form-control text-right" v-bind:name="'item_disc_value['+itemIndex+'][]'" v-model="discount.disc_value" placeholder="Nominal" v-on:keyup="discountNominalToPercent(item, discount)" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right" colspan="3">@lang('purchase_order.create.table.total.body.sub_total_discount')</td>
                                                    <td class="text-right" colspan="2"> @{{ discountItemSubTotal(item.discounts) }}</td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <td width="65%"
                                                class="text-right">@lang('purchase_order.create.table.total.body.total_discount')</td>
                                            <td width="35%" class="text-right">
                                                <span class="control-label-normal">@{{ discountTotal() }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('sales_order.revise.box.expenses')</h3>
                            @if($currentSo->status == 'SOSTATUS.WD')
                                <button type="button" class="btn btn-primary btn-xs pull-right"
                                        v-on:click="insertExpense()"><span class="fa fa-plus fa-fw"/></button>
                            @endif
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="expensesListTable" class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th width="20%">@lang('sales_order.revise.table.expense.header.name')</th>
                                            <th width="20%"
                                                class="text-center">@lang('sales_order.revise.table.expense.header.type')</th>
                                            <th width="10%"
                                                class="text-center">@lang('purchase_order.revise.table.expense.header.internal_expense')</th>
                                            <th width="25%"
                                                class="text-center">@lang('sales_order.revise.table.expense.header.remarks')</th>
                                            <th width="5%">&nbsp;</th>
                                            <th width="20%"
                                                class="text-center">@lang('sales_order.revise.table.expense.header.amount')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(expense, expenseIndex) in so.expenses">
                                            <td>
                                                <input type="hidden" name="expense_id[]" v-bind:value="expense.id"/>
                                                <input name="expense_name[]" type="text" class="form-control"
                                                       v-model="expense.name"
                                                       data-parsley-required="true" {{ $currentSo->status == 'SOSTATUS.WD' ? '' : 'readonly' }} />
                                            </td>
                                            <td>
                                                @if($currentSo->status == 'SOSTATUS.WD')
                                                    <input type="hidden" name="expense_type[]" v-bind:value="expense.type.code">
                                                    <select data-parsley-required="true"
                                                            class="form-control" v-model="expense.type">
                                                        <option v-bind:value="{code: ''}">@lang('labels.PLEASE_SELECT')</option>
                                                        <option v-for="expenseType in expenseTypes" v-bind:value="expenseType">@{{ expenseType.description }}</option>
                                                    </select>
                                                @else
                                                    <input type="text" class="form-control" readonly
                                                           v-bind:value="expense.type.description">
                                                    <input type="hidden" name="expense_type[]"
                                                           v-bind:value="expense.type.code"/>
                                                @endif
                                            </td>
                                            <td class="text-center" width="10%">
                                                <input name="is_internal_expense[]" v-model="expense.is_internal_expense" type="checkbox">
                                            </td>
                                            <td>
                                                <input name="expense_remarks[]" type="text" class="form-control"
                                                       v-model="expense.remarks" {{ $currentSo->status == 'SOSTATUS.WD' ? '' : 'readonly' }}/>
                                            </td>
                                            <td class="text-center">
                                                @if($currentSo->status == 'SOSTATUS.WD')
                                                    <button type="button" class="btn btn-danger btn-md"
                                                            v-on:click="removeExpense(expenseIndex)"><span
                                                                class="fa fa-minus"></span>
                                                    </button>
                                                @endif
                                            </td>
                                            <td>
                                                <input name="expense_amount[]" type="text"
                                                       class="form-control text-right"
                                                       v-model="expense.amount" data-parsley-required="true">
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="expensesTotalListTable" class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <td width="80%"
                                                class="text-right">@lang('sales_order.create.table.total.body.total')</td>
                                            <td width="20%" class="text-right">
                                                <span class="control-label-normal">@{{ expenseTotal() }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('sales_order.create.box.transaction_summary')</h3>
                        </div>
                        <div class="box-body">
                            @for ($i = 0; $i < 23; $i++)
                                <br/>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"><h3 class="box-title">@lang('purchase_order.create.box.discount_transaction')</h3></h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="discountsListTable" class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th width="30%" class="text-right">@lang('purchase_order.create.table.total.body.total')</th>
                                            <th width="30%" class="text-left">@lang('purchase_order.create.table.total.body.invoice_discount')</th>
                                            <th width="40%" class="text-right">@lang('purchase_order.create.table.total.body.total_transaction')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-right valign-middle">@{{ ( grandTotal() - discountTotal() ) + expenseTotal() }}</td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <input type="text" class="form-control text-right" v-bind:name="'disc_percent'" v-model="so.disc_percent" placeholder="%" v-on:keyup="discountTotalPercentToNominal()" />
                                                        </div>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control text-right" v-bind:name="'disc_value'" v-model="so.disc_value" placeholder="Nominal" v-on:keyup="discountTotalNominalToPercent()" />
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-right valign-middle">@{{ ( grandTotal() - discountTotal() ) + expenseTotal()   }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('sales_order.revise.box.remarks')</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <textarea id="inputRemarks" class="form-control" rows="5"
                                                      name="remarks">{{ $currentSo->remarks }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-7 col-offset-md-5">
                    <div class="btn-toolbar">
                        <button id="submitButton" type="submit" class="btn btn-primary pull-right">
                            @lang('buttons.submit_button')</button>
                        &nbsp;&nbsp;&nbsp;
                        <a id="printButton" href="#" target="_blank" class="btn btn-primary pull-right">
                            @lang('buttons.print_preview_button')</a>&nbsp;&nbsp;&nbsp;
                        <a id="cancelButton" href="{{ route('db.so.revise.index') }}"
                           class="btn btn-primary pull-right" role="button">@lang('buttons.cancel_button')</a>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}

        @include('sales_order.customer_details_partial')
    </div>
@endsection

@section('custom_js')
    <script type="application/javascript">
        var currentSo = JSON.parse('{!! htmlspecialchars_decode($currentSo->toJson()) !!}');

        var soAppVue = new Vue({
            el: '#soVue',
            data: {
                vendorTruckingDDL: JSON.parse('{!! htmlspecialchars_decode($vendorTruckingDDL) !!}'),
                warehouseDDL: JSON.parse('{!! htmlspecialchars_decode($warehouseDDL) !!}'),
                expenseTypes: JSON.parse('{!! htmlspecialchars_decode($expenseTypes) !!}'),
                productDDL: JSON.parse('{!! htmlspecialchars_decode($productDDL) !!}'),
                stocksDDL: JSON.parse('{!! htmlspecialchars_decode($stocksDDL) !!}'),
                so: {
                    disc_percent : currentSo.disc_percent % 1 !== 0 ? currentSo.disc_percent : parseFloat(currentSo.disc_percent).toFixed(0),
                    disc_value : currentSo.disc_value % 1 !== 0 ? currentSo.disc_value : parseFloat(currentSo.disc_value).toFixed(0),
                    stock: {id: ''},
                    product: {id: ''},
                    customer: currentSo.customer ? _.cloneDeep(currentSo.customer) : {id: ''},
                    warehouse: _.cloneDeep(currentSo.warehouse),
                    vendorTrucking: currentSo.vendor_trucking ?  _.cloneDeep(currentSo.vendor_trucking) : {id: 0, name: ''},
                    items: [],
                    expenses: []
                }
            },
            methods: {
                discountPercentToNominal: function(item, discount){
                    var disc_value = ( item.selected_unit.conversion_value * item.quantity * item.price ) * ( discount.disc_percent / 100 );
                    if( disc_value % 1 !== 0 )
                        disc_value = disc_value.toFixed(2);
                    discount.disc_value = disc_value;
                },
                discountNominalToPercent: function(item, discount){
                    var disc_percent = discount.disc_value / ( item.selected_unit.conversion_value * item.quantity * item.price ) * 100 ;
                    if( disc_percent % 1 !== 0 )
                        disc_percent = disc_percent.toFixed(2);
                    discount.disc_percent = disc_percent;
                },
                discountItemSubTotal: function (discounts) {
                    var result = 0;
                    _.forEach(discounts, function (discount) {
                        result += parseFloat(discount.disc_value);
                    });
                    if( result % 1 !== 0 )
                        result = result.toFixed(2);
                    return result;
                },
                discountTotal: function (index) {
                    var vm = this;
                    var result = 0;
                    _.forEach(vm.so.items, function (item, key) {
                        _.forEach(item.discounts, function (discount) {
                            result += parseFloat(discount.disc_value);
                        });
                    });
                    return result;
                },
                grandTotal: function () {
                    var vm = this;
                    var result = 0;
                    _.forEach(vm.so.items, function (item, key) {
                        result += (item.selected_unit.conversion_value * item.quantity * item.price);
                    });
                    return result;
                },
                expenseTotal: function () {
                    var vm = this;
                    var result = 0;
                    _.forEach(vm.so.expenses, function (expense, key) {
                        if (expense.type.code === 'EXPENSETYPE.ADD')
                            result += parseInt(numeral().unformat(expense.amount));
                        else
                            result -= parseInt(numeral().unformat(expense.amount));
                    });
                    return result;
                },
                discountTotalPercentToNominal: function(){
                    var vm = this;
                    
                    var grandTotal = 0;
                    _.forEach(vm.so.items, function (item, key) {
                        grandTotal += (item.selected_unit.conversion_value * item.quantity * item.price);
                    });
                    
                    var discountTotal = 0;
                    _.forEach(vm.so.items, function (item, key) {
                        _.forEach(item.discounts, function (discount) {
                            discountTotal += parseFloat(discount.disc_value);
                        });
                    });
                    
                    var expenseTotal = 0;
                    _.forEach(vm.so.expenses, function (expense, key) {
                        if (expense.type.code === 'EXPENSETYPE.ADD')
                            expenseTotal += parseInt(numeral().unformat(expense.amount));
                        else
                            expenseTotal -= parseInt(numeral().unformat(expense.amount));
                    });
                    
                    var disc_value = ( ( grandTotal - discountTotal ) + expenseTotal ) * ( vm.so.disc_percent / 100 );
                    if( disc_value % 1 !== 0 )
                        disc_value = disc_value.toFixed(2);
                    vm.so.disc_value = disc_value;
                },
                discountTotalNominalToPercent: function(){
                    var vm = this;
                    
                    var grandTotal = 0;
                    _.forEach(vm.so.items, function (item, key) {
                        grandTotal += (item.selected_unit.conversion_value * item.quantity * item.price);
                    });
                    
                    var discountTotal = 0;
                    _.forEach(vm.so.items, function (item, key) {
                        _.forEach(item.discounts, function (discount) {
                            discountTotal += parseFloat(discount.disc_value);
                        });
                    });
                    
                    var expenseTotal = 0;
                    _.forEach(vm.so.expenses, function (expense, key) {
                        if (expense.type.code === 'EXPENSETYPE.ADD')
                            expenseTotal += parseInt(numeral().unformat(expense.amount));
                        else
                            expenseTotal -= parseInt(numeral().unformat(expense.amount));
                    });
                    
                    var disc_percent = vm.so.disc_value / ( ( grandTotal - discountTotal ) + expenseTotal ) * 100 ;
                    if( disc_percent % 1 !== 0 )
                        disc_percent = disc_percent.toFixed(2);
                    vm.so.disc_percent = disc_percent;
                },
                insertProduct: function (product) {
                    if(product.id !== ''){
                        var vm = this;
                        var item_init_discount = [];
                        item_init_discount.push({
                            disc_percent : 0,
                            disc_value : 0,
                        });
                        vm.so.items.push({
                            stock: {
                                id: 0
                            },
                            product: product,
                            selected_unit: {
                                unit: {
                                    id: ''
                                },
                                conversion_value: 1
                            },
                            base_unit: _.find(product.product_units, isBase),
                            quantity: 0,
                            price: 0,
                            discounts: item_init_discount,
                        });
                    }
                },
                insertStock: function (stock) {
                    if(stock.id !== ''){
                        var vm = this;
                        var stock_price = _.find(stock.today_prices, function (price) {
                            return price.price_level_id === vm.so.customer.price_level_id;
                        });
                        var item_init_discount = [];
                        item_init_discount.push({
                            disc_percent : 0,
                            disc_value : 0,
                        });

                        vm.so.items.push({
                            stock: _.cloneDeep(stock),
                            product: _.cloneDeep(stock.product),
                            selected_unit: {
                                unit: {
                                    id: ''
                                },
                                conversion_value: 1
                            },
                            base_unit: _.cloneDeep(_.find(stock.product.product_units, isBase)),
                            quantity: 0,
                            price: stock_price ? stock_price : 0,
                            discounts: item_init_discount,
                        });
                    }
                },
                removeItem: function (index) {
                    this.so.items.splice(index, 1);
                },
                insertDiscount: function (item) {
                    item.discounts.push({
                        disc_percent : 0,
                        disc_value : 0,
                    });
                },
                removeDiscount: function (index, discountIndex) {
                    var vm = this;
                    this.so.items[index].discounts.splice(discountIndex, 1);
                },
                insertExpense: function () {
                    this.so.expenses.push({
                        name: '',
                        type: {
                            code: ''
                        },
                        is_internal_expense: false,
                        amount: 0,
                        remarks: ''
                    });

                    $('input[type="checkbox"], input[type="radio"]').iCheck({
                        checkboxClass: 'icheckbox_square-blue',
                        radioClass: 'iradio_square-blue'
                    });
                },
                removeExpense: function (index) {
                    this.so.expenses.splice(index, 1);
                }
            }
        });

        for (var i = 0; i < currentSo.items.length; i++) {
            var itemDiscounts = [];
            if( currentSo.items[i].discounts.length ){
                for (var ix = 0; ix < currentSo.items[i].discounts.length; ix++) {
                    itemDiscounts.push({
                        id : currentSo.items[i].discounts[ix].id,
                        disc_percent : currentSo.items[i].discounts[ix].item_disc_percent % 1 !== 0 ? currentSo.items[i].discounts[ix].item_disc_percent : parseFloat(currentSo.items[i].discounts[ix].item_disc_percent).toFixed(0),
                        disc_value : currentSo.items[i].discounts[ix].item_disc_value % 1 !== 0 ? currentSo.items[i].discounts[ix].item_disc_value : parseFloat(currentSo.items[i].discounts[ix].item_disc_value).toFixed(0),
                    });
                }
            }
            else{
                itemDiscounts.push({
                    disc_percent : 0,
                    disc_value : 0,
                });
            }
            soAppVue.so.items.push({
                id: currentSo.items[i].id,
                stock: {
                    id: currentSo.items[i].stock_id
                },
                product: _.cloneDeep(currentSo.items[i].product),
                base_unit: _.cloneDeep(_.find(currentSo.items[i].product.product_units, isBase)),
                selected_unit: _.cloneDeep(_.find(currentSo.items[i].product.product_units, getSelectedUnit(currentSo.items[i].selected_unit_id))),
                quantity: currentSo.items[i].quantity % 1 != 0 ? parseFloat(currentSo.items[i].quantity).toFixed(1) : parseFloat(currentSo.items[i].quantity).toFixed(0),
                price: parseFloat(currentSo.items[i].price).toFixed(0),
                discounts : itemDiscounts
            });
        }

        for (var i = 0; i < currentSo.expenses.length; i++) {
            var type = _.find(soAppVue.expenseTypes, function (type) {
                return type.code === currentSo.expenses[i].type;
            });

            soAppVue.so.expenses.push({
                id: currentSo.expenses[i].id,
                name: currentSo.expenses[i].name,
                type: _.cloneDeep(type),
                is_internal_expense: currentSo.expenses[i].is_internal_expense == 1,
                amount: currentSo.expenses[i].amount,
                remarks: currentSo.expenses[i].remarks
            });
        }

        function getSelectedUnit(selectedUnitId) {
            return function (element) {
                return element.unit_id == selectedUnitId;
            }
        }

        function isBase(unit) {
            return unit.is_base == 1;
        }

        $(function () {
            $('input[type="checkbox"], input[type="radio"]').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue'
            });

            $("#inputSoDate").datetimepicker({
                format: "DD-MM-YYYY hh:mm A",
                defaultDate: moment()
            });
            $("#inputShippingDate").datetimepicker({
                format: "DD-MM-YYYY hh:mm A",
                defaultDate: moment()
            });
        });
    </script>
@endsection