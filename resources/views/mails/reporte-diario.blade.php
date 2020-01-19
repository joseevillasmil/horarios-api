<p><b>Pacientes confirmados para el día de hoy.</b></p>
<table style="width:100%">
    <thead>
        <th>Nombre del paciente</th>
        <th>Cedula o Pasaporte</th>
        <th>Correo</th>
        <th>Teléfono</th>
        <th>Hora de atención</th>
    </thead>
    <tbody>
        @foreach($citas as $cita)
            <tr>
                <td>{{$cita->cliente->nombre}}</td>
                <td>{{$cita->cliente->dni}}</td>
                <td>{{$cita->cliente->correo}}</td>
                <td>{{$cita->cliente->telefono}}</td>
                <td>{{$cita->inicio->format('d/m/Y H:i')}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
