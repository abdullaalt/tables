auth = {
	
	token: false,

	init: function(){
		
		$('.container').load('/pages/sign.html', "f" + (new Date()).valueOf(), function(){
			
		});
		
	},
	
	authorize: function(e){

		e.preventDefault()
		fd = new FormData(document.getElementById('auth-form'))
		fd.append('device_name', 'site')

		xhr.sendRequest('/sanctum/token', 'authResult', null, auth, 'POST', fd)
		
	},

	authResult: function(s, data, el){
		if (s > 200) {
			alert('Неправильный логин или пароль')
			return false
		}
		this.token = data.token
		console.log(data);
		console.log(data.token);
		console.log(this.token);
		$.cookie('token', this.token, { expires: 60, path: '/' });
		window.location.reload()
	}
	
}