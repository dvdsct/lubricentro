<div>
    <div class="card">
        <div class="card-header">
            <!--  <h3 class="card-title">Tarjetas</h3> -->
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Tarjeta</th>
                        <th style="width: 200px">Descuento</th>
                        <th style="width: 200px">Interes</th>
                        <th>Plan Cuotas</th>
                        <th style="width: 100px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tarjetas as $t)
                    @livewire('tarjeta-credito' , ['tarjeta' => $t , 'key'=> $t->id])
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            <ul class="pagination pagination-sm m-0 float-right">
                <li class="page-item"><a class="page-link" href="#">«</a></li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">»</a></li>
            </ul>
        </div>
    </div>
</div>