@foreach ($datas as $data1)
    <select class="form-select mb-2" name="isi_{{ $loop->iteration }}">
        <option value="">Hapus</option>
        @foreach ($Levels as $level)
            <option value="{{ $level->Id_Level }}" <?php echo $data1->Id_Level == $level->Id_Level ? 'selected' : ''; ?>>{{ $level->Nama_level }}</option>
        @endforeach
    </select>
    <input type="hidden" name="id_{{ $loop->iteration }}" value="{{ $data1->ID_Modul_List }}">
    <?php $ulang = $loop->count; ?>
@endforeach

<?php if (!isset($ulang)) {
    $ulang = 0;
} ?>

<input type="hidden" name="jumlah" value="{{ $ulang }}">
<select class="form-select mb-2" id="tambahakses" onchange="kliktambahakses()">
    <option selected value=""disabled>Tambah Hak Akses</option>
    @foreach ($Levels as $level)
        <option value="{{ $level->Id_Level }}">{{ $level->Nama_level }}</option>
    @endforeach
</select>
