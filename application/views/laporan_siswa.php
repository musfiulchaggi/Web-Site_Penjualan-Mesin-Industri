<div class="col bg-primary text-left">
    <h2>
        <center>Data Siswa</center>
    </h2>
</div>
<hr />
<table border="1" width="100%" style="text-align:center;">
    <tr>
        <th style="background-color: #D6EEEE;">No</th>
        <th>Nama</th>
        <th>Kelas</th>
        <th>Jenis Kelamin</th>
        <th>Alamat</th>
    </tr>
    <?php
    $no = 1;


    //Use this code to convert your image to base64
    // Apply this in a view 

    $path = "https://static.remove.bg/remove-bg-web/ea4eaf12fdb825d09a927ec03bfcfc723af95931/assets/start_remove-c851bdf8d3127a24e2d137a55b1b427378cd17385b01aec6e59d5d4b5f39d2ec.png"; // Modify this part (your_img.png
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

    foreach ($siswa as $row) {
    ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $row->nama; ?></td>
            <td><?php echo $row->kelas; ?></td>
            <td><?php echo $row->jenis_kelamin; ?></td>
            <td><?php echo $row->alamat; ?></td>
        </tr>
    <?php
    }
    ?>
</table>