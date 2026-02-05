@extends('layouts.app')
<style>
/* Force big modal */
.billing-modal {
    width: 98vw !important;
    max-width: 98vw !important;
    margin: 1vh auto !important;
}

/* Full height feel */
.billing-modal .modal-content {
    height: 96vh;
}

/* Proper scrolling */
.billing-modal .modal-body {
    overflow-y: auto;
}

.billing-cell {
    height: 160px;
    position: relative;
}

.billing-content {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    padding: 8px 0;
}

.billing-divider {
    height: 1px;
    background: #eee;
    margin: 8px 0;
}

.transfer-arrows {
    position: absolute;
    top: calc(50% + 6px);
    left: 50%;
    transform: translate(-50%, -50%);
    display: flex;
    gap: 6px;
    background: #fff;
    padding: 3px 6px;
    border-radius: 6px;
    z-index: 10;
}

/* ✅ PAGINATION STYLES */
.pagination {
    margin-bottom: 0;
}

.page-link {
    color: #0d6efd;
    border-color: #dee2e6;
}

.page-link:hover {
    color: #0a58ca;
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.page-item.disabled .page-link {
    color: #6c757d;
    background-color: #fff;
    border-color: #dee2e6;
}
</style>

@section('title', 'customers')

@section('content')
<div class="container-fluid">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">ગ્રાહકો</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addConsumerModal">
            <i class="bi bi-plus-circle"></i> ગ્રાહક ઉમેરો
        </button>
    </div>

   <div class="card mb-3">
    <div class="card-body">
        <form id="filterForm">
            <div class="row g-2">

                <!-- Search -->
                <div class="col-md-3">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control"
                           placeholder="Search by name / account / mobile /co. no.">
                </div>

                <!-- Zone -->
                <div class="col-md-2">
                    <select name="zone_id" id="zoneFilter" class="form-select">
                        <option value="">બધા ઝોન</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}"
                                {{ request('zone_id') == $zone->id ? 'selected' : '' }}>
                                {{ $zone->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Area -->
                <div class="col-md-2">
                    <select name="area_id" id="areaFilter" class="form-select">
                        <option value="">બધા વિસ્તાર</option>
                    </select>
                </div>

                <!-- Per Page Selector -->
                <div class="col-md-2">
                    <select name="per_page" class="form-select">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 per page</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 per page</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per page</option>
                    </select>
                </div>

                <!-- Reset -->
                <div class="col-md-2 d-grid">
                    <button type="button" class="btn btn-outline-secondary" onclick="resetFilters()">
                        રીસેટ
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
 <!-- Table -->
<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>ગ્રાહક નં.</th>
                    <th>નામ</th>
                    <th>અકાઉંટ નં.</th>
                    <th>મોબાઇલ</th>
                    <th>ઝોન</th>
                    <th>વિસ્તાર</th>
                    <th>આઈડી</th>
                    <th width="140">Action</th>
                </tr>
            </thead>

            <tbody id="customerTableBody">
                @include('admin.customers.partials.table-rows')
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    @if($customers->hasPages())
        <div class="card-footer">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-muted small" id="paginationInfo">
                        Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} 
                        of {{ $customers->total() }} entries
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end" id="paginationLinks">
                        @include('admin.customers.partials.pagination')
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
{{-- Add Consumer Modal (UI only for now) --}}
<div class="modal fade" id="addConsumerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('customers.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">ગ્રાહક ઉમેરો</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
    <label class="form-label">ગ્રાહક નં.</label>
    <input name="customer_number"
           class="form-control"
           required>
</div>
                    <div class="col-md-6">
                        <label class="form-label">નામ</label>
                        <input name="name" class="form-control" required>
                    </div>


                    <div class="col-md-6">
                        <label class="form-label">અકાઉંટ નં.</label>
                        <input name="account_number" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">મોબાઇલ</label>
                        <input name="mobile" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">આઈડી</label>
                        <select name="site_id" class="form-select" required>
                            @foreach($sites as $site)
                                <option value="{{ $site->id }}">{{ $site->site_code }}</option>
                            @endforeach
                        </select>
                    </div>

                  <div class="col-md-6">
    <label class="form-label">ઝોન</label>
    <select name="zone_id" id="zoneSelect" class="form-select" required>
        <option value="">ઝોન પસંદ કરો</option>
        @foreach($zones as $zone)
            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
        @endforeach
    </select>
</div>


                    <div class="col-md-6">
                        <label class="form-label">વિસ્તાર</label>
                      <select name="area_id" id="areaSelect" class="form-select" required>
    <option value="">વિસ્તાર પસંદ કરો</option>
</select>



                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editConsumerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="editConsumerForm" class="modal-content">
            @csrf
            @method('PUT')

            <input type="hidden" id="editCustomerId">
            <input type="hidden" id="id">

            <div class="modal-header">
                <h5 class="modal-title">ગ્રાહક વિગત સુધારો</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">ગ્રાહક નં.</label>
                        <input name="customer_number"
                               id="editCustomerNumber"
                               class="form-control"
                               >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">નામ</label>
                        <input name="name"
                               id="editName"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">અકાઉંટ નં.</label>
                        <input name="account_number"
                               id="editAccount"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">મોબાઇલ</label>
                        <input name="mobile"
                               id="editMobile"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">ઝોન</label>
                        <select name="zone_id"
                                id="editZone"
                                class="form-select"
                                required></select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">વિસ્તાર</label>
                        <select name="area_id"
                                id="editArea"
                                class="form-select"
                                required></select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">આઈડી</label>
                        <select name="site_id"
                                id="editSite"
                                class="form-select"
                                required>
                            @foreach($sites as $site)
                                <option value="{{ $site->id }}">
                                    {{ $site->site_code }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button class="btn btn-primary">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Billing View Modal --}}
<div class="modal fade" id="billingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable billing-modal">
        <div class="modal-content">

            <!-- Header -->
         <div class="modal-header bg-primary text-white">
    <h5 class="modal-title">
        <i class="bi bi-receipt"></i> Customer Billing Details
    </h5>

    <div>
        <!-- <button class="btn btn-sm btn-light me-2"
                onclick="openAddBillModal()">
            <i class="bi bi-plus-circle"></i> Add Bill
        </button> -->

        <button type="button"
                class="btn-close btn-close-white"
                data-bs-dismiss="modal"></button>
    </div>
</div>


            <div class="modal-body">

                <!-- CUSTOMER INFO -->
                <div class="card mb-3">
                    <div class="card-body py-2">
                        <div class="row text-sm">
                            <div class="col-md-3">
                                <strong>નામ:</strong>
                                <span id="billCustomerName">-</span>
                            </div>
                            <div class="col-md-3">
                                <strong>અકાઉંટ નં.:</strong>
                                <span id="billAccount">-</span>
                            </div>
                            <div class="col-md-3">
                                <strong>મોબાઇલ:</strong>
                                <span id="billMobile">-</span>
                            </div>
                            <div class="col-md-3">
                                <strong>આઈડી:</strong>
                                <span id="billSite">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BILLING TABLE -->
                <div class="card">
                   <div class="card-header">
    <div class="d-flex w-100 align-items-center">
        <strong>બિલો ની હિસ્ટરી</strong>

        <div class="ms-auto">
            <button class="btn btn-sm btn-success"
                onclick="openAddBillModal()">
                <i class="bi bi-plus-circle"></i> બિલ બનાવો
            </button>
        </div>
    </div>
</div>

<div id="billingYears"></div>




                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="addBillModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="addBillForm" class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle"></i> બિલ ઉમેરો
                </h5>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="billCustomerId" name="customer_id">

                <div class="row g-3">

    <div class="col-md-6">
        <label class="form-label">વર્ષ</label>
        <select name="bill_year" id="billYear" class="form-select" required>
            @for($y = now()->year; $y >= now()->year - 10; $y--)
                <option value="{{ $y }}">{{ $y }}</option>
            @endfor
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">મહિનો</label>
        <select name="bill_month" id="billMonth" class="form-select" required>
            @foreach(range(1,12) as $m)
                <option value="{{ $m }}">
                    {{ date('F', mktime(0,0,0,$m,1)) }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- ✅ ADDED FIELD (ONLY THIS) -->
    <div class="col-md-6">
        <label class="form-label">બિલ ની તારીખ</label>
        <input type="date"
               name="bill_date"
               class="form-control"
               required>
    </div>

  <div class="col-md-6">
    <label class="form-label text-success fw-bold">
        જમા રકમ
    </label>
    <input type="number"
           name="credit_amount"
           class="form-control border-success"
           min="0"
           required>
</div>

<div class="col-md-6">
    <label class="form-label text-danger fw-bold">
        બાકી રકમ
    </label>
    <input type="number"
           name="debit_amount"
           class="form-control border-danger"
           min="0"
           required>
</div>


</div>


            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary"
                        data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary">
                    Save Bill
                </button>
            </div>

        </form>
    </div>
</div>
<div class="modal fade"
     id="transferModal"
     tabindex="-1"
     data-bs-backdrop="static"
     data-bs-keyboard="false">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="transferTitle"></h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="billId">
        <input type="hidden" id="direction">

        <label class="form-label">રકમ</label>
        <input type="number" id="transferAmount"
               class="form-control"
               min="1">
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" onclick="submitTransfer()">Transfer</button>
      </div>

    </div>
  </div>
</div>
<div class="modal fade" id="transactionHistoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">
                    <i class="bi bi-clock-history"></i>
                    ચુકવણી નો હિસાબ —
                    <span id="historyMonth"></span>
                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <table class="table table-bordered table-sm text-center">
<thead class="table-light">
    <tr>
        <th>Flow</th>
        <th>Change</th>
        <th>Date</th>
    </tr>
</thead>


                    <tbody id="transactionHistoryBody">
                        <tr>
                            <td colspan="4">Loading...</td>
                        </tr>
                    </tbody>
                </table>

            </div>

        </div>
    </div>
</div>
@push('scripts')

<script>
const filterForm = document.getElementById('filterForm');

/* FILTER DROPDOWNS */
const zoneFilter = document.getElementById('zoneFilter');
const areaFilter = document.getElementById('areaFilter');

/* MODAL DROPDOWNS */
const zoneSelect = document.getElementById('zoneSelect');
const areaSelect = document.getElementById('areaSelect');

function loadAreas(zoneId, targetSelect, selectedArea = null) {
    if (!targetSelect) {
        console.warn('Target select element not found');
        return;
    }
    
    if (!zoneId) {
        targetSelect.innerHTML = '<option value="">Select Area</option>';
        return;
    }

    let url = `{{ route('areas.byZone', ':id') }}`.replace(':id', zoneId);

    fetch(url)
        .then(res => res.json())
        .then(data => {
            let options = '<option value="">Select Area</option>';
            data.forEach(area => {
                let selected = selectedArea == area.id ? 'selected' : '';
                options += `<option value="${area.id}" ${selected}>${area.name}</option>`;
            });
            targetSelect.innerHTML = options;
        })
        .catch(err => console.error('Error loading areas:', err));
}

// ✅ FUNCTION: Fetch next customer number
function fetchNextCustomerNumber() {
    const zoneSelectElement = document.getElementById('zoneSelect');
    const areaSelectElement = document.getElementById('areaSelect');
    const customerNumberInput = document.querySelector('#addConsumerModal input[name="customer_number"]');
    
    if (!zoneSelectElement || !areaSelectElement || !customerNumberInput) {
        console.warn('Required form elements not found');
        return;
    }
    
    const zoneId = zoneSelectElement.value;
    const areaId = areaSelectElement.value;

    if (!zoneId || !areaId) {
        customerNumberInput.value = '';
        return;
    }

    fetch(`{{ route('customers.nextNumber') }}?zone_id=${zoneId}&area_id=${areaId}`)
        .then(res => res.json())
        .then(data => {
            customerNumberInput.value = data.customer_number;
        })
        .catch(err => {
            console.error('Error fetching customer number:', err);
        });
}

/* FILTER: Zone → Area + Load Page */
if (zoneFilter) {
    zoneFilter.addEventListener('change', function () {
        loadAreas(this.value, areaFilter);
        setTimeout(() => {
            if (typeof loadPage === 'function') {
                loadPage(1);
            }
        }, 150);
    });
}

/* FILTER: Area → Load Page */
if (areaFilter) {
    areaFilter.addEventListener('change', function () {
        if (typeof loadPage === 'function') {
            loadPage(1);
        }
    });
}

/* MODAL: Zone → Area */
if (zoneSelect) {
    zoneSelect.addEventListener('change', function () {
        loadAreas(this.value, areaSelect);
        const customerNumberInput = document.querySelector('#addConsumerModal input[name="customer_number"]');
        if (customerNumberInput) {
            customerNumberInput.value = '';
        }
    });
}

/* MODAL: Area → Fetch Customer Number */
if (areaSelect) {
    areaSelect.addEventListener('change', function () {
        fetchNextCustomerNumber();
    });
}

/* PAGE LOAD → restore filter area */
document.addEventListener('DOMContentLoaded', function () {
    let selectedZone = "{{ request('zone_id') }}";
    let selectedArea = "{{ request('area_id') }}";

    if (selectedZone && areaFilter) {
        loadAreas(selectedZone, areaFilter, selectedArea);
    }
});
</script>

<script>
// ✅ SERVER-SIDE PAGINATION
document.addEventListener('click', function(e) {
    if (e.target.matches('.page-link[data-page]') || e.target.closest('.page-link[data-page]')) {
        e.preventDefault();
        
        const link = e.target.matches('.page-link[data-page]') 
            ? e.target 
            : e.target.closest('.page-link[data-page]');
        
        const page = link.getAttribute('data-page');
        
        loadPage(page);
    }
});

function loadPage(page) {
    const tableBody = document.getElementById('customerTableBody');
    if (!tableBody) {
        console.error('Table body not found');
        return;
    }
    
    // Show loading indicator
    tableBody.innerHTML = `
        <tr>
            <td colspan="9" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </td>
        </tr>
    `;

    // Get current filters
    const searchInput = document.querySelector('input[name="search"]');
    const zoneFilterEl = document.getElementById('zoneFilter');
    const areaFilterEl = document.getElementById('areaFilter');
    const perPageSelect = document.querySelector('select[name="per_page"]');
    
    const search = searchInput?.value || '';
    const zoneId = zoneFilterEl?.value || '';
    const areaId = areaFilterEl?.value || '';
    const perPage = perPageSelect?.value || 10;

    // Build URL with query parameters
    const params = new URLSearchParams({
        page: page,
        search: search,
        zone_id: zoneId,
        area_id: areaId,
        per_page: perPage
    });

    // Remove empty parameters
    for (let [key, value] of [...params.entries()]) {
        if (!value) params.delete(key);
    }

    // Fetch data
    fetch(`{{ route('customers.index') }}?${params.toString()}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update table body
        tableBody.innerHTML = data.html;
        
        // Update pagination links
        const paginationContainer = document.getElementById('paginationLinks');
        if (paginationContainer) {
            paginationContainer.innerHTML = data.pagination;
        }
        
        // Update pagination info
        const infoContainer = document.getElementById('paginationInfo');
        if (infoContainer) {
            infoContainer.innerHTML = data.info;
        }
        
        // Scroll to top of table (smooth)
        const tableCard = document.querySelector('.card');
        if (tableCard) {
            tableCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    })
    .catch(error => {
        console.error('Error loading page:', error);
        tableBody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center text-danger py-3">
                    <i class="bi bi-exclamation-triangle"></i> Error loading data. Please try again.
                </td>
            </tr>
        `;
    });
}

// ✅ Per page selector
const perPageSelect = document.querySelector('select[name="per_page"]');
if (perPageSelect) {
    perPageSelect.addEventListener('change', function() {
        loadPage(1);
    });
}

// ✅ Search with debounce
let searchTimeout;
const searchInput = document.querySelector('input[name="search"]');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadPage(1);
        }, 500);
    });
}

// ✅ Reset filters function
function resetFilters() {
    const searchInput = document.querySelector('input[name="search"]');
    const zoneFilterEl = document.getElementById('zoneFilter');
    const areaFilterEl = document.getElementById('areaFilter');
    const perPageSelect = document.querySelector('select[name="per_page"]');
    
    if (searchInput) searchInput.value = '';
    if (zoneFilterEl) zoneFilterEl.value = '';
    if (areaFilterEl) areaFilterEl.innerHTML = '<option value="">All Areas</option>';
    if (perPageSelect) perPageSelect.value = '10';
    
    loadPage(1);
}
</script>

<script>
// ✅ BILLING FUNCTIONS
function loadBilling(customerId) {
    currentCustomerId = customerId;
    const modal = document.getElementById('billingModal');
    if (modal) {
        modal.setAttribute('data-customer', customerId);
    }
    
    fetch(`/customers/${customerId}/billing`)
        .then(res => res.json())
        .then(data => {
            const billCustomerName = document.getElementById('billCustomerName');
            const billAccount = document.getElementById('billAccount');
            const billMobile = document.getElementById('billMobile');
            const billSite = document.getElementById('billSite');
            
            if (billCustomerName) billCustomerName.innerText = data.customer.name;
            if (billAccount) billAccount.innerText = data.customer.account;
            if (billMobile) billMobile.innerText = data.customer.mobile;
            if (billSite) billSite.innerText = data.customer.site;

            let html = '';

            data.years.forEach((yearBlock, index) => {
                let show = index === 0 ? 'show' : '';

                html += `
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong>${yearBlock.year}</strong>
                        <div>
                            <button class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="collapse"
                                data-bs-target="#year-${yearBlock.year}">
                                View
                            </button>
                        </div>
                    </div>

                    <div class="collapse ${show}" id="year-${yearBlock.year}">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center mb-0">
                                <thead class="table-light">
                                    <tr>
                                        ${yearBlock.months.map(m => `
                                            <th class="text-center">
                                                <div class="d-flex justify-content-center align-items-center gap-1">
                                                    <span>${m.name}</span>
                                                    ${m.bill_id ? `
                                                        <button class="btn btn-xs btn-outline-secondary"
                                                            title="ચુકવણી નો હિસાબ"
                                                            onclick="openTransactionHistory(${m.bill_id}, '${m.name}', ${yearBlock.year})">
                                                            <i class="bi bi-clock-history"></i>
                                                        </button>
                                                    ` : ''}
                                                </div>
                                            </th>
                                        `).join('')}
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        ${yearBlock.months.map(m => `
                                            <td class="position-relative text-center billing-cell">
                                                <div class="billing-content">
                                                    <div class="text-success fw-bold">${m.credit ?? '-'}</div>
                                                    ${m.bill_id ? `
                                                        <div class="transfer-arrows">
                                                            <button class="btn btn-sm btn-outline-success"
                                                                title="Debit → Credit"
                                                                onclick="openTransferModal(${m.bill_id}, 'debit_to_credit')">
                                                                <i class="bi bi-arrow-up"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-danger"
                                                                title="Credit → Debit"
                                                                onclick="openTransferModal(${m.bill_id}, 'credit_to_debit')">
                                                                <i class="bi bi-arrow-down"></i>
                                                            </button>
                                                        </div>
                                                    ` : ''}
                                                    <div class="text-danger fw-bold">${m.debit ?? '-'}</div>
                                                    <div class="billing-divider"></div>
                                                    <div class="small text-muted mt-auto">${m.date ?? '-'}</div>
                                                </div>
                                            </td>
                                        `).join('')}
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>`;
            });

            const billingYears = document.getElementById('billingYears');
            if (billingYears) {
                billingYears.innerHTML = html;
            }
        })
        .catch(err => console.error('Error loading billing:', err));
}
</script>

<script>
let currentCustomerId = null;

function openAddBillModal() {
    const billCustomerIdInput = document.getElementById('billCustomerId');
    if (billCustomerIdInput) {
        billCustomerIdInput.value = currentCustomerId;
    }

    const today = new Date();
    const year  = today.getFullYear();
    const month = today.getMonth() + 1;
    const date  = today.toISOString().split('T')[0];

    const billYearSelect = document.getElementById('billYear');
    const billMonthSelect = document.getElementById('billMonth');
    const billDateInput = document.querySelector('#addBillModal input[name="bill_date"]');
    
    if (billYearSelect) billYearSelect.value = year;
    if (billMonthSelect) billMonthSelect.value = month;
    if (billDateInput) billDateInput.value = date;

    const modalEl = document.getElementById('addBillModal');
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl, { backdrop: 'static' });
        modal.show();
    }
}
</script>

<script>
const addBillForm = document.getElementById('addBillForm');
if (addBillForm) {
    addBillForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch(`/billing`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(res => res.json())
        .then(resp => {
            if (resp.status) {
                const modalEl = document.getElementById('addBillModal');
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) {
                    modalInstance.hide();
                }
                loadBilling(currentCustomerId);
            }
        })
        .catch(err => console.error('Error saving bill:', err));
    });
}

document.addEventListener('show.bs.modal', function () {
    if (document.querySelectorAll('.modal.show').length > 1) {
        document.body.classList.add('modal-open');
    }
});
</script>

<script>
function openTransferModal(billId, direction) {
    const billIdInput = document.getElementById('billId');
    const directionInput = document.getElementById('direction');
    const transferTitle = document.getElementById('transferTitle');
    const transferAmountInput = document.getElementById('transferAmount');
    
    if (billIdInput) billIdInput.value = billId;
    if (directionInput) directionInput.value = direction;
    if (transferTitle) {
        transferTitle.innerText = direction === 'debit_to_credit' ? 'Debit → Credit' : 'Credit → Debit';
    }
    if (transferAmountInput) transferAmountInput.value = '';

    const modalEl = document.getElementById('transferModal');
    if (modalEl) {
        new bootstrap.Modal(modalEl).show();
    }
}

function submitTransfer() {
    const billIdInput = document.getElementById('billId');
    const directionInput = document.getElementById('direction');
    const transferAmountInput = document.getElementById('transferAmount');
    
    if (!billIdInput || !directionInput || !transferAmountInput) {
        console.error('Transfer form elements not found');
        return;
    }
    
    fetch("{{ route('billing.transfer') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            bill_id: billIdInput.value,
            direction: directionInput.value,
            amount: transferAmountInput.value
        })
    })
    .then(r => r.json())
    .then(res => {
        if (!res.status) {
            alert(res.message);
            return;
        }

        const modalEl = document.getElementById('transferModal');
        const modalInstance = bootstrap.Modal.getInstance(modalEl);
        if (modalInstance) {
            modalInstance.hide();
        }

        loadBilling(currentCustomerId);
    })
    .catch(err => console.error('Error transferring:', err));
}
</script>

<script>
function openTransactionHistory(billId, monthName, year) {
    const historyMonth = document.getElementById('historyMonth');
    if (historyMonth) {
        historyMonth.innerText = `${monthName} ${year}`;
    }

    const modalEl = document.getElementById('transactionHistoryModal');
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl, { backdrop: 'static' });
        modal.show();
    }

    fetch(`/billing/${billId}/transactions`)
        .then(res => res.json())
        .then(resp => {
            let rows = '';

            if (!resp.length) {
                rows = `<tr><td colspan="4">No transactions found</td></tr>`;
            } else {
                resp.forEach(tx => {
                    let flowLabel = '';
                    let changeText = '';
                    let badgeClass = '';

                    if (tx.direction === 'debit_to_credit') {
                        flowLabel = 'Debit → Credit';
                        changeText = `${tx.before_debit} → ${tx.after_debit}`;
                        badgeClass = 'bg-success';
                    }

                    if (tx.direction === 'credit_to_debit') {
                        flowLabel = 'Credit → Debit';
                        changeText = `${tx.before_credit} → ${tx.after_credit}`;
                        badgeClass = 'bg-danger';
                    }
if (tx.direction === 'bill_created') {
    flowLabel = 'Bill Created';
                        changeText = `${tx.amount}`;
                        badgeClass = 'bg-primary';
}

                    rows += `
                        <tr>
                            <td><span class="badge ${badgeClass}">${flowLabel}</span></td>
                            <td class="fw-bold">${changeText}</td>
                            <td>${tx.date}</td>
                        </tr>
                    `;
                });
            }

            const transactionHistoryBody = document.getElementById('transactionHistoryBody');
            if (transactionHistoryBody) {
                transactionHistoryBody.innerHTML = rows;
            }
        })
        .catch(err => console.error('Error loading transactions:', err));
}
</script>

<script>
// ✅ ADD CONSUMER MODAL - Pre-fill zone/area from filters
const addConsumerModal = document.getElementById('addConsumerModal');
if (addConsumerModal) {
    addConsumerModal.addEventListener('show.bs.modal', function () {
        const selectedZone = zoneFilter?.value || '';
        const selectedArea = areaFilter?.value || '';
        const zoneSelectElement = document.getElementById('zoneSelect');
        const areaSelectElement = document.getElementById('areaSelect');
        const customerNumberInput = document.querySelector('#addConsumerModal input[name="customer_number"]');

        if (!zoneSelectElement || !areaSelectElement) {
            console.warn('Modal form elements not found');
            return;
        }

        if (selectedZone) {
            zoneSelectElement.value = selectedZone;
            loadAreas(selectedZone, areaSelectElement, selectedArea);

            if (selectedArea) {
                setTimeout(() => {
                    fetchNextCustomerNumber();
                }, 300);
            }
        } else {
            zoneSelectElement.value = '';
            areaSelectElement.innerHTML = '<option value="">Select Zone First</option>';
            if (customerNumberInput) {
                customerNumberInput.value = '';
            }
        }
    });

    // ✅ RESET FORM WHEN MODAL CLOSES
    addConsumerModal.addEventListener('hidden.bs.modal', function () {
        const form = this.querySelector('form');
        if (form) {
            form.reset();
        }
        
        const customerNumberInput = document.querySelector('#addConsumerModal input[name="customer_number"]');
        const zoneSelectElement = document.getElementById('zoneSelect');
        const areaSelectElement = document.getElementById('areaSelect');
        
        if (customerNumberInput) customerNumberInput.value = '';
        if (zoneSelectElement) zoneSelectElement.value = '';
        if (areaSelectElement) areaSelectElement.innerHTML = '<option value="">Select Zone First</option>';
    });
}

// ✅ ADD CONSUMER FORM SUBMISSION (AJAX)
const addConsumerForm = document.querySelector('#addConsumerModal form');
if (addConsumerForm) {
    addConsumerForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn ? submitBtn.innerHTML : 'Save';
        
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
        }

        const formData = new FormData(this);

        fetch('{{ route("customers.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.errors) {
                let errorMsg = 'Please fix the following errors:\n\n';
                Object.values(data.errors).forEach(errors => {
                    errors.forEach(error => {
                        errorMsg += '• ' + error + '\n';
                    });
                });
                alert(errorMsg);
            } else if (data.status || data.success) {
                const modalEl = document.getElementById('addConsumerModal');
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) {
                    modalInstance.hide();
                }
                
                
                if (typeof loadPage === 'function') {
                    loadPage(1);
                } else {
                    window.location.reload();
                }
            } else {
                alert(data.message || 'Failed to add customer');
            }
            
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    });
}
</script>

<script>
function openEditConsumer(id) {
    fetch(`/admin/customers/${id}/data`)
        .then(res => res.json())
        .then(c => {
            const editCustomerId = document.getElementById('editCustomerId');
            const idField = document.getElementById('id');
            const editCustomerNumber = document.getElementById('editCustomerNumber');
            const editName = document.getElementById('editName');
            const editAccount = document.getElementById('editAccount');
            const editMobile = document.getElementById('editMobile');
            const editSite = document.getElementById('editSite');
            const editZone = document.getElementById('editZone');
            const editArea = document.getElementById('editArea');
            
            if (editCustomerId) editCustomerId.value = c.id;
            if (idField) idField.value = c.id;
            if (editCustomerNumber) editCustomerNumber.value = c.customer_number;
            if (editName) editName.value = c.name;
            if (editAccount) editAccount.value = c.account_number;
            if (editMobile) editMobile.value = c.mobile;
            if (editSite) editSite.value = c.site_id;

            if (editZone) {
                let zones = '';
                @foreach($zones as $zone)
                    zones += `<option value="{{ $zone->id }}">{{ $zone->name }}</option>`;
                @endforeach
                editZone.innerHTML = zones;
                editZone.value = c.zone_id;
            }

            if (editArea) {
                loadAreas(c.zone_id, editArea, c.area_id);
            }

            const modalEl = document.getElementById('editConsumerModal');
            if (modalEl) {
                new bootstrap.Modal(modalEl).show();
            }
        })
        .catch(err => console.error('Error loading customer:', err));
}
</script>

<script>
const editConsumerForm = document.getElementById('editConsumerForm');
if (editConsumerForm) {
    editConsumerForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const editCustomerId = document.getElementById('editCustomerId');
        if (!editCustomerId) {
            console.error('Customer ID not found');
            return;
        }
        
        const id = editCustomerId.value;
        const formData = new FormData(this);

        fetch(`/admin/customers/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-HTTP-Method-Override': 'PUT',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(resp => {
            if (!resp.status) return;

            const c = resp.customer;
            const row = document.getElementById(`customer-row-${c.id}`);

            if (row) {
                const tdCustomerNumber = row.querySelector('.td-customer-number');
                const tdName = row.querySelector('.td-name');
                const tdAccount = row.querySelector('.td-account');
                const tdMobile = row.querySelector('.td-mobile');
                const tdZone = row.querySelector('.td-zone');
                const tdArea = row.querySelector('.td-area');
                const tdSite = row.querySelector('.td-site');
                
                if (tdCustomerNumber) tdCustomerNumber.innerText = c.customer_number;
                if (tdName) tdName.innerText = c.name;
                if (tdAccount) tdAccount.innerText = c.account_number;
                if (tdMobile) tdMobile.innerText = c.mobile;
                if (tdZone) tdZone.innerText = c.zone?.name ?? '-';
                if (tdArea) tdArea.innerText = c.area?.name ?? '-';
                if (tdSite) tdSite.innerText = c.site?.site_code ?? '-';
            }

            const modalEl = document.getElementById('editConsumerModal');
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) {
                modalInstance.hide();
            }
        })
        .catch(err => console.error('Error updating customer:', err));
    });
}
</script>

@endpush

@endsection
