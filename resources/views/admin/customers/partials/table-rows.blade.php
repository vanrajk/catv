@forelse($customers as $index => $consumer)
    <tr id="customer-row-{{ $consumer->id }}">
        <td>{{ $customers->firstItem() + $index }}</td>
        <td class="td-customer-number">{{ $consumer->customer_number }}</td>
        <td class="td-name">{{ $consumer->name }}</td>
        <td class="td-account">{{ $consumer->account_number }}</td>
        <td class="td-mobile">{{ $consumer->mobile }}</td>
        <td class="td-zone">{{ $consumer->zone->name ?? '-' }}</td>
        <td class="td-area">{{ $consumer->area->name ?? '-' }}</td>
        <td class="td-site">{{ $consumer->site->site_code ?? '-' }}</td>

        <td>
            <button class="btn btn-sm btn-info"
                data-bs-toggle="modal"
                data-bs-target="#billingModal"
                onclick="loadBilling({{ $consumer->id }})">
                <i class="bi bi-receipt"></i>
            </button>

            <button class="btn btn-sm btn-warning"
                onclick="openEditConsumer({{ $consumer->id }})">
                <i class="bi bi-pencil"></i>
            </button>

            <form action="{{ route('customers.destroy', $consumer->id) }}"
                  method="POST"
                  class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger"
                        onclick="return confirm('Delete this consumer?')">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="9" class="text-center py-3">
            No customers found
        </td>
    </tr>
@endforelse