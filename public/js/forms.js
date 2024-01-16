var forms = {
	
    getFieldsForm: function(){

        _form = $('<form></form>')
        ff = $('<div class="form-fields"></div>')
        console.log(fields.current_model_fields);
        for (key in fields.current_model_fields){
            element = fields.current_model_fields[key]
            if (element.type != 'parent_id' && element.name != 'id'){
                item = fields.getInput(element)
                ff.append(item)
            }
        }

        _form.append(ff)
        _form = this.addButtonsToForm(_form)

        return _form

    },

    addButtonsToForm: function(_form){

        btns = $('<div class="form-buttons"></div>')
        btns.append(templates.getButton({
            text: 'Сохранить',
            clickaction: 'content.saveItem'
        }))

        btns.append(templates.getButton({
            text: 'Отмена',
            clickaction: 'forms.cancelForm'
        }))

        _form.append(btns)

        return _form

    },

    fill: function(form, item){

        for (key in item){
            el = form.find('[name='+key+']')
            if (el.length > 0){
                form.find('[name='+key+']').val(item[key])
            }
        }

    },

    cancelForm: function(e){
        e.preventDefault()
        document.onclick()
    },

    sendForm: function(fd, url, callback, object){

        xhr.sendRequest(url, callback, null, object, 'POST', fd)

    },

    getFormData: function(_form){

        return new FormData(_form)

    },

    getAddFormTmpl: function(){
        return templates.getAddFormTmpl()
    }

}