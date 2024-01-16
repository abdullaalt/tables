var fields = {

    current_model_fields: null,
    varchar: string,
    checkbox: checkbox,
    text: text,
    list: list,
    multiple: multiple,
    parent_id: parent_id,
    edit_mode: false,
    edit_field: false,
    modal: false,

    getFields: function(model = false){
       
        if (!model){
            model = content.current_model
        }

        xhr.sendRequest('/fields?model='+model.name, 'fillFields', null, fields)

    },

    fillFields: function(s, result, el){ 

        this.current_model_fields = result
        content.getCurrentModelData()

    },

    getInputValue: function(type, value){
        return this[type].getInputValue(value)
    },

    getInput: function(item, value = ''){
        
        input = this[item.type].getInput(item, value)

        return input

    },

    getOutput: function(type, value, item_id = false, field_id = false){
        if (!type){
            return value
        }
        
        output = this[type].getOutput(value, item_id, field_id)

        return output
    },

    callback: function(field, field_html){
        this[field.type].callback(field, field_html)
    },

    addField: function(e){

        form = forms.getAddFormTmpl()

        modal = modals.getModalsContainer(form, 'center', true, true)
        this.modal = modal

        modals.show(modal, 'fields.resetEditMode')

    },

    saveField: function(e){

        e.preventDefault()
        fd = forms.getFormData(e.target.parentElement.parentElement)
        callback = 'addedField'
        if (this.edit_mode){
            fd.append('field_id', this.edit_field.id)
            callback = 'saveField'
        }
        forms.sendForm(fd, '/content/fields/'+content.current_model.name, callback, fields)

    },

    savedField: function(s, data, el){

        this.current_model_fields[data.id] = data
        $('.th-field-id-'+data.id).text(data.title)
        modals.hide(this.modal)

    },

    addedField: function(s, data, el){
        this.current_model_fields.push(data)
        this.fieldsChange()
    },

    fieldsChange: function(){

        field = this.current_model_fields.slice(-1)[0]
        console.log(field);
        if (field.is_show){
            table.addColumn(field.title, field.in_filter)
        }

    },

    fieldsVisibleControl: function(e){

        container = templates.getFieldsControlPanel()

        coords = {
            left: e.target.offsetLeft,
            top: e.target.offsetTop+45,
            width: 250
        }

        modal = modals.getModalsContainer(container, 'manual', false, true, coords)
        this.modal = modal
        modals.show(modal, 'fields.resetEditMode')

    },

    toggleField: function(field_id, el){

        old_status = el.data('show')
        new_status = !old_status === true ? 1 : 0

        for (key in fields.current_model_fields){

            if (fields.current_model_fields[key].id == field_id){
                fields.current_model_fields[key].is_show = new_status
                break
            }

        }

        el.data('show', new_status)
        el.removeClass('visible-'+old_status).addClass('visible-'+new_status)

        xhr.sendRequest('/fields/status/'+field_id+'/'+new_status, 'setFieldShowStatus', el, fields)

    },

    setFieldShowStatus: function(field_id, status, el){

        table.reDrawTable()

    },

    getFieldById: function(field_id){

        res = false
        
        for (key in fields.current_model_fields){

            if (fields.current_model_fields[key].id == field_id){
                res = fields.current_model_fields[key]
                break
            }

        }

        return res

    },

    editField: function(id, el){

        modals.hide(this.modal)
        form = forms.getAddFormTmpl()
        //modal = modals.getModalsContainer(form, 'center', true, true)
        field = this.getFieldById(id)
        //form = forms.fill(form, field)
        modal = modals.getModalsContainer(form, 'center', true, true)
        modals.show(modal, 'fields.resetEditMode')
        forms.fill(modal.find('form'), field)
        modal.find('input[name=name]').attr('readonly', 'readonly')
        this.modal = modal
        this.edit_mode = true
        this.edit_field = field
    },

    resetEditMode: function(){
        this.edit_mode = false
        this.edit_field = false
        this.modal = false
    },

    setFieldSettings: function(e){
        if (e.target.value == 'list') $('.field-settings').css('display', 'flex')
        else $('.field-settings').css('display', 'none')
    }

}