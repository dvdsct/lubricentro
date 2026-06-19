<div>

    @if ($modalProductos == true)

    <!-- MODAL PARA CARGAR UN NUEVO PRODUCTO -->
    <div class="modal fade show" id="modal-default" style="display: block; padding-right: 17px;background-color:rgba(0, 0, 0, 0.5)" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title"> <strong> DATOS DEL PRODUCTO </strong> </h4>
                    <button type="button" class="close" wire:click='modalProductosOn'>
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">

                    @if(session()->has('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="m-3">

                        <div class="text-center form-group">
                            <h4 style="font-style: italic;"> {{ $producto->descripcion ?? '' }}</h4>
                        </div>

                        <div class="pt-4 row">
                            <div class="col">
                                <select class="form-control" aria-label="Default select example" wire:model='categoria' wire:change='selTipo'>
                                    <option selected>Categoria</option>
                                    @foreach ($categorias as $ca)
                                    <option value="{{ $ca->id }}">{{ $ca->descripcion }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('categoria')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        @if ($formDes)
                        <div class="pt-4 row">
                            <div class="col-md">
                                <select class="form-control" aria-label="Default select example" wire:model='subcategoria' wire:change='selSubTipo'>
                                    <option selected>Subcategoria</option>
                                    @foreach ($subcategorias as $sub)
                                    <option value="{{ $sub[0] }}">
                                        {{ $sub[1] }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('subcategoria')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                        </div>

                        @endif
                        @if ($formProd)

                        <div class="pt-4 row">
                            <div class="col-md">
                                <select class="form-control" aria-label="Default select example" wire:model='subcategoria'>
                                    <option selected>Subcategoria</option>
                                    @foreach ($subcategorias as $sub)
                                    <option value="{{ $sub->id }}">
                                        {{ $sub->descripcion }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('subcategoria')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                        </div>
                        @endif


                        {{-- Descripcion y Codigo --}}
                        <div class="pt-4 row">
                            <div class="col-md-8">
                                <input type="text" class="form-control" wire:model='descripcion' placeholder="Producto" />
                                @error('descripcion')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <input type="text" class="form-control" wire:model='codigo' placeholder="Codigo" />
                                @error('codigo')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        @if ($formProd)
                        {{-- Costo y Precio de venta --}}
                        <div class="pt-4 row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" step="0.01" class="form-control" wire:model.live='costo' placeholder="Costo proveedor">
                                    @error('costo')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" step="0.01" class="form-control" wire:model='precioVenta' placeholder="Precio de venta">
                                    @error('precioVenta')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @endif

                        @if ($formDes)
                        {{-- Porcentaje o Monto --}}
                        <div class="pt-4 row">
                            @if ($tipoDes)
                            <div class="col-md">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <input type="text" class="form-control" wire:model='porcentaje' placeholder="Porcentaje">

                                </div>
                            </div>
                            @else
                            <div class="col-md">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="text" class="form-control" wire:model='monto' placeholder="Monto">
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif


                        @can('stock')
                        {{-- Codigo de barra y Stock --}}
                        <div class="pt-4 row">
                            <div class="col-md-7">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                    </div>
                                    <input type="text" class="form-control" wire:model='cod_barra' placeholder="Codigo de barras" />
                                </div>
                            </div>

                            @if (!$producto)
                            <div class="col-md-5">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-boxes"></i></span>
                                    </div>
                                    <input type="number" class="form-control" wire:model='stock' placeholder="Stock Inicial" />
                                </div>
                            </div>
                            @endif
                        </div>

                        @if ($producto)
                        <!-- Panel de Corrección de Stock para producto existente -->
                        <div class="card mt-3 bg-light border-warning shadow-sm">
                            <div class="card-header bg-warning py-2 text-dark font-weight-bold">
                                <i class="fas fa-tools mr-1"></i> Corrección de Stock
                            </div>
                            <div class="card-body py-3">
                                <div class="row align-items-center">
                                    <div class="col-md-6 mb-2">
                                        <div class="bg-white border rounded p-2 text-center shadow-xs">
                                            <span class="d-block text-muted text-uppercase text-xs font-weight-bold">Stock Actual</span>
                                            <h3 class="m-0 font-weight-bold text-primary">{{ $stockActual }}</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group mb-0">
                                            <label class="font-weight-bold mb-1 text-sm">Modo de ajuste</label>
                                            <div class="d-flex justify-content-around">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" wire:model.live="stockMode" id="modeAjustar" value="ajustar">
                                                    <label class="form-check-label text-sm" for="modeAjustar">Sumar/Restar</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" wire:model.live="stockMode" id="modeFijar" value="fijar">
                                                    <label class="form-check-label text-sm" for="modeFijar">Establecer Fijo</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    @if ($stockMode === 'ajustar')
                                    <div class="col-md-6">
                                        <div class="form-group mb-2">
                                            <label for="stockDelta" class="text-sm font-weight-bold">Ajustar cantidad (+ / -)</label>
                                            <input type="number" id="stockDelta" class="form-control form-control-sm" wire:model.live="stockDelta" placeholder="Ej: 5 o -3">
                                            @error('stockDelta') <small class="text-danger d-block">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                    @else
                                    <div class="col-md-6">
                                        <div class="form-group mb-2">
                                            <label for="stockFinal" class="text-sm font-weight-bold">Nuevo stock total</label>
                                            <input type="number" id="stockFinal" class="form-control form-control-sm" wire:model.live="stockFinal" placeholder="Ej: 20">
                                            @error('stockFinal') <small class="text-danger d-block">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                    @endif

                                    <div class="col-md-6">
                                        <div class="form-group mb-2">
                                            <label for="stockMotivo" class="text-sm font-weight-bold">Motivo del ajuste</label>
                                            <input type="text" id="stockMotivo" class="form-control form-control-sm" wire:model="stockMotivo" placeholder="Ej: Ajuste manual">
                                            @error('stockMotivo') <small class="text-danger d-block">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                </div>

                                @if (isset($stockPreview) && $stockPreview !== '')
                                <div class="alert alert-warning py-1 px-3 mb-2 mt-2 text-center text-sm">
                                    Stock final proyectado: <strong>{{ $stockPreview }}</strong>
                                </div>
                                @endif

                                <button type="button" class="btn btn-warning btn-sm btn-block mt-3 font-weight-bold" wire:click="applyStockCorrection">
                                    <i class="fas fa-check mr-1"></i> Aplicar Ajuste
                                </button>
                            </div>
                        </div>
                        @endif
                        @endcan


                        @if ($formProd)
                        {{-- Proveedor --}}
                        <div class="pt-4 row">
                            <div class="col">
                                <select class="form-control" aria-label="Default select example" wire:model='proveedor'>
                                    @foreach ($proveedores as $pro)
                                    <option value="{{ $pro->id }}">
                                        {{ $pro->perfiles->personas->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif

                        @if ($formProd)
                        <!-- Sección de producto provisional -->
                        <div class="pt-4">
                            @if(auth()->user()->hasRole('admin'))
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="es_provisional" wire:model="es_provisional">
                                    <label class="form-check-label" for="es_provisional">
                                        Marcar como producto provisional
                                    </label>
                                    <small class="form-text text-muted">
                                        Los productos provisionales no afectan al stock y son visibles solo para administradores.
                                    </small>
                                </div>
                            @else
                                <div class="alert alert-info py-2">
                                    <i class="fas fa-info-circle"></i> Este producto será marcado como provisional.
                                </div>
                                <input type="hidden" wire:model="es_provisional" value="1">
                            @endif
                        </div>
                        @endif

                        <!-- Espacio adicional para asegurar que el botón sea accesible -->
                        <div class="pt-4 pb-4"></div>

                    </div>
                </div>
                <div class="modal-footer justify-content-between sticky-bottom bg-white pt-2 pb-2" style="box-shadow: 0 -2px 10px rgba(0,0,0,0.1);">
                    <button type="button" class="btn btn-default" wire:click='modalProductosOn'>Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click='saveproduct' wire:loading.attr="disabled" wire:target="saveproduct">
                        <span wire:loading.remove wire:target="saveproduct">Guardar</span>
                        <span wire:loading wire:target="saveproduct"><i class="fas fa-spinner fa-spin"></i> Guardando...</span>
                    </button>
                </div>
                </form>
            </div>

        </div>

    </div>
    @endif

    @script
    <script>
        $wire.on('nonDesc', (event) => {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No tienes autorizacion para crear descuentos",
            });
        });
    </script>
@endscript
</div>
