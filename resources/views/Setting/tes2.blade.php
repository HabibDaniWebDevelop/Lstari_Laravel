<!DOCTYPE html>
<html>
<head>
	<title>Menu Options</title>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
	<h1>Select Menu Options</h1>
	<form id="menu-form">
		<select name="menu-option-1" class="menu-option">
			<option value="">-- Select Option --</option>
			<option value="menu-1">Menu 1</option>
			<option value="menu-2">Menu 2</option>
			<option value="menu-3">Menu 3</option>
		</select>
		<br><br>
		<select name="menu-option-2" class="menu-option">
			<option value="">-- Select Option --</option>
			<option value="menu-1">Menu 1</option>
			<option value="menu-2">Menu 2</option>
			<option value="menu-3">Menu 3</option>
		</select>
		<br><br>
		<select name="menu-option-3" class="menu-option">
			<option value="">-- Select Option --</option>
			<option value="menu-1">Menu 1</option>
			<option value="menu-2">Menu 2</option>
			<option value="menu-3">Menu 3</option>
		</select>
		<br><br>
		<select name="menu-option-4" class="menu-option">
			<option value="">-- Select Option --</option>
			<option value="menu-1">Menu 1</option>
			<option value="menu-2">Menu 2</option>
			<option value="menu-3">Menu 3</option>
		</select>
		<br><br>
		<select name="menu-option-5" class="menu-option">
			<option value="">-- Select Option --</option>
			<option value="menu-1">Menu 1</option>
			<option value="menu-2">Menu 2</option>
			<option value="menu-3">Menu 3</option>
		</select>
		<br><br>
		<input type="button" id="save-menu-options" value="Save" onclick="simpan()">
	</form>

	<script>

        function simpan(){
            var cek = cekForm();
            if (cek) {
                alert('Data sudah lengkap');
            }
        }

        function cekForm(){
            var cek = true;
            $('select[name*="menu"]').each(function() {
                if ($(this).val() === '') {
                alert('Tolong isi dulu ' + $(this).attr('name'));
                cek = false;
                return false;
                }
            });
            return cek;
        }

        
	</script>
</body>
</html>
