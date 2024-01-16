var xhr = {
	
	endpoint: 'https://crm.kod06.ru/api/v1',
	
	sendRequest: function(url, callback, el = null, obj=window, method = "GET", data = null){
		this.showLoader(el)
		const request = new XMLHttpRequest(); 
		request.open(method, this.endpoint+url, true);
		request.setRequestHeader('Authorization', 'Bearer '+auth.token);
		request.setRequestHeader('User-Token', '1');
		request.addEventListener("readystatechange", () => {
			xhr.removeLoader(el)
			//data = JSON.parse(request.response)
			if (request.status > 399 && request.status != 401){
				app.showErrorBlock(`Ошибка ${request.status}: ${data.errors}`); // Например, 404 : Not Found
			}else if (request.readyState === 4) {
				data = JSON.parse(request.response)
				var s = request.status
				obj[callback](s, data, el)
				
			}
		});
				 
			// Выполняем запрос 
			
		request.send(data);
				
		return request
	},

	showLoader: function(el){
		if (!el){
			el = app.main
		}
		el.css('position', 'relative')
		el.append(templates.getLoader())

	},

	removeLoader: function(el){
		$('.loader').remove()
		if (!el){
			el = app.main
		}
		el.css('position', '')
	}
	
}