<html>
<head>
	<script src="scripts/jquery-1.8.3.js "></script>
</head>
<body>
	<table width="300" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
		<tr>
			<form id="formLogin" name="formLogin">
				<td>
					<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
						<tr>
							<td colspan="3"><strong>Member Login </strong></td>
						</tr>
						<tr>
							<td width="78">Username</td>
							<td width="6">:</td>
							<td width="294"><input name="myusername" type="text" id="myusername"></td>
						</tr>
						<tr>
							<td>Password</td>
							<td>:</td>
							<td><input name="mypassword" type="password" id="mypassword"></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td><input type="button" id="login" name="login" value="Login"></td>
						</tr>
					</table>
				</td>
			</form>
		</tr>
	</table>
	<label id="result"></label>
	<script>
		$('#login').click(function(){
			$.get('/?checklogin&'+$('#formLogin').serialize(), function(data){
				if(data == 'success')
					location.href = '/?list';
				else
					$('#result').html(data);
			});
		});
	</script>
</body>
</html>
