<div>

    @if ($modal == true)

        <div class="modal fade show" id="modal-lg" style="display: block; padding-right: 17px;" aria-modal="true"
            role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $montoAPagar }}</h4>
                        <button type="button" class="close" wire:click='closeModal'>
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <form wire:submit.prevent="guardarPago">
                                <div class="mb-3">
                                    <label for="tipo_pago" class="form-label">Tipo de Pago</label>
                                    <select wire:model.live="tipoPago" id="tipo_pago" class="form-select">
                                        <option selected>Open this select menu</option>

                                        @foreach ($tiposPago as $t)
                                            <option value="{{ $t->id }}">{{ $t->descripcion }}</option>
                                        @endforeach

                                    </select>
                                </div>
                                @if ($tipoPago == 2 ?? $tipoPago == 3 ?? $tipoPago == 4)

                                    <div class="mb-3">
                                        <label for="medio_pago" class="form-label">Medio de Pago</label>
                                            <select class="form-select" aria-label="Default select example" wire:model.live="medioPago" id="medio_pago" >
                                                <option selected>Open this select menu</option>
                                            @foreach ($mediosPago as $m)
                                                <option value="{{ $m->id }}">{{ $m->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                @endif
                                @if ($tipoPago == 1)
                                    <div class="mb-3">
                                        <label for="tipo_pago" class="form-label">Tipo de Pago</label>
                                        <select wire:model.live="cliente" id="tipo_pago" class="form-select">
                                            <option selected>Open this select menu</option>

                                            @foreach ($clientes as $c)
                                                <option value="{{ $c->id }}">{{ $c->perfiles->personas->nombre .' '. $c->perfiles->personas->apellido .' '. $c->perfiles->personas->dni }}</option>
                                            @endforeach

                                        </select>
                                    </div>


                                @endif

                                @if ($medioPago == 2)
                                    <div class="mb-3">
                                        <label for="efectivo" class="form-label">Efectivo</label>
                                        <input wire:model.live="efectivo" type="number" id="efectivo"
                                            class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label for="vuelto" class="form-label">Vuelto</label>
                                        <label for="vuelto" class="form-label">{{ $vuelto }}</label>
                                    </div>
                                @endif

                                @if ($medioPago == 1)
                                    <div class="mb-3">
                                        <label for="codeCard" class="form-label">Codigo Posnet</label>
                                        <input wire:model="codeCard" type="number" id="codeCard" class="form-control">
                                    </div>
                                @endif

                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" wire:click='closeModal'>Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </form>
                    </div>
                </div>

            </div>

        </div>

    @endif


</div>
