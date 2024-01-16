var models = {

    models: null,
    edit_mode: false,
    edit_model: false,
    current_model: false,
    current_modal: false,

    add: function(e){

        e.preventDefault()
        html = templates.getAddModelTmpl()
        modal = modals.getModalsContainer(html, 'center', true, true)
        modals.show(modal, 'models.resetModelsEditMode')
        this.current_modal = modal

    },

    saveModel: function(e){

        e.preventDefault()
        fd = forms.getFormData(e.target.parentElement.parentElement)
        if (this.edit_mode){
            fd.append('model_id', this.edit_model.id)
        }
        forms.sendForm(fd, '/models', 'savedModel', models)

    },

    savedModel: function(s, data, el){

        this.models.push(data.data)
        menu.addMenuItem(data.data)
        modals.hide(this.current_modal)

    },

    resetModelsEditMode: function(){
        this.edit_mode = false
        this.edit_model = false
    }

}