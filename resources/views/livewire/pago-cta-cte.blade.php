<div>
    <tr>
        <td>{{ $pago->id }}

        <td>{{ $pago->created_at->translatedFormat('j \d\e F \d\e\l Y') }}</td>
        <td>{{ $pago->concepto }}</td>
        <td>${{ $pago->total }}</td>
        <td>{{ $pago->estado }}</td>
        <td><button class="btn btn-danger btn-sm" 
            wire:click='$dispatch("pagar")'
            >Cobrar</button>
        </td>


    </tr>

    {{-- @livewire('form-pago', ['orden' => $orden]) --}}

</div>
