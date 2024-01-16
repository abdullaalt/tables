var content = {
    current_model: null,
    current_data: false,
    current_item: false, 
    edit_el: false,
    editing_el: false,

    init: function(){

        app.btns.html('')
        app.content.find('*').not('.loader').remove()
        table.current_table = false

        fields.getFields()
        
        this.addButtons()
    },

    getCurrentModelData: function(){

        xhr.sendRequest('/content/'+this.current_model.name, 'printData', null, content)

    },

    printData: function(s, result, el){

        app.content.find('*').not('.loader').remove()
        this.current_data = result.data

        current_table = templates.getTables(fields.current_model_fields)

        current_table.append(templates.fillTableWithData(result.data))
        table.current_table = current_table

        app.content.append(current_table)

        app.registerScroll()
        filter.setResetBtns()

    },

    addButtons: function(){

        title2 = this.current_model.title2 ? this.current_model.title2 : 'элемент'
        app.btns.html('')

        app.btns.append($('#btnTmpl').tmpl({
            className: '',
            clickaction: 'fields.addField',
            text: 'Добавить поле'
        }))

        app.btns.append($('#btnTmpl').tmpl({
            className: '',
            clickaction: 'content.addItem',
            text: 'Добавить '+ title2
        }))

        app.btns.append($('#btnTmpl').tmpl({
            className: 'fields-list',
            clickaction: 'fields.fieldsVisibleControl',
            text: '<span></span><span></span><span></span>'
        }))

    },

    addItem: function(){

        form = forms.getFieldsForm()

        modal = modals.getModalsContainer(form, 'center', true, true)

        modals.show(modal)

        app.change()

    },

    saveItem: function(e){

        e.preventDefault()
        forms.sendForm(forms.getFormData(e.target.parentElement.parentElement), '/content/'+content.current_model.name, 'addedItem', content)

    },

    addedItem: function(s, data, el){

        table.addRow(data)

    },

    showMoreModelElement: function(el){

        _parent = el.closest('.multiple-select-list')
        
        ul = _parent.find('ul').clone()
        ul.addClass('open-multiple-select-list')

        coords = {
            left: _parent.offset().left,
            top: _parent.offset().top+35,
            width: _parent.width()
        }

        modal = modals.getModalsContainer(ul, 'manual', false, true, coords)
        modals.show(modal)

    },

    unlinkModelElement: function(el, id, item_id, field_id){

        //if (prompt('Вы уверены что хотите отвязать запись. Она также будет отвязана и в связанной таблице?')){

            field = fields.getFieldById(field_id)
            console.log(field);
            fd = new FormData()
            fd.append('parent_model', this.current_model.name)
            fd.append('child_model', field.connect_model)
            fd.append('parent_item_id', item_id)
            fd.append('child_item_id', id)
            fd.append('field_name', field.name)
            fd.append('field_id', field.id)

            if (field.type == 'parent_id'){ // если связан через внешнюю связь
                fd.append('mode', 'parent')
            }else{
                if (field.is_connect){
                    if (field.multiple){
                        fd.append('mode', 'unlink')
                    }else{
                        fd.append('mode', 'child')
                    }
                }else{
                    fd.append('mode', 'list')
                }
            }  
            
            xhr.sendRequest('/content/unlink', 'unlinkSuccess', el, content, 'POST', fd)

        //}

    },

    unlinkSuccess: function(s, data, el){
        li_class = el.closest('li').attr('class')
        $('.'+li_class, el.closest('div')).remove()
    },

    initEditMode: function(el){

        // if (this.edit_el){
        //     this.outFromEditMode
        // }

        field = fields.getFieldById(el.data('field_id'))

        if (!field.can_edit) return false

        this.edit_el = el
        
        old_width = el.width()
        el.width(old_width)
        content.current_item = content.getItemById(el.data('item_id'))
        
        field_html = fields.getInput(field, content.current_item[field.name].value)
        field_html.find('label').remove()
        el.html('')
        el.append(field_html)
        modals.uc = app.getUniqueClass('td_edit_cell')
        modals.is_hide_outside_click = true
        el.addClass(modals.uc)
        el.addClass('td_edit_cell')
        app.change()
        field_html.find('input, select, textarea').keydown(function(e){
            if (e.keyCode  == 27){
                content.updateCell()
                modals.is_hide_outside_click = false
            }

            if (e.keyCode == 13){
                content.outFromEditMode()
                modals.is_hide_outside_click = false
            }
        })

        fields.callback(field, field_html)
        
        //e.target.inner

    },

    outFromEditMode: function(){
        
        this.editing_el = this.edit_el
        this.edit_el = false
        this.saveCell()

    },

    saveCell: function(){

        fd = new FormData()
        field = fields.getFieldById(this.editing_el.data('field_id'))
        if (field.type == 'checkbox'){
            value = this.editing_el.find('input').prop('checked')
        }else{
            value = this.editing_el.find('input, select, textarea').val()
            if (!value || value == "[object Object]") {
                this.updateCell()
                return true
            }
        }
//console.log(value)
        

        fd.append('value', value)
        fd.append('field_id', field.id)
        fd.append('item_id', this.current_item.id.value)

        xhr.sendRequest('/content/save/cell', 'cellSaved', this.editing_el, content, 'POST', fd)

    },

    cellSaved: function(s, data, el){

        this.current_item[field.name].value = data.value
        this.updateCell()

    },

    updateCell: function(){
        field = fields.getFieldById(this.editing_el.data('field_id'))
        field_html = fields.getOutput(field.type, this.current_item[field.name].value, this.current_item.id.value, field.id)
        this.editing_el.html('')
        this.editing_el.append(field_html)
        this.current_item = false
        this.editing_el.width('')
        this.editing_el.removeClass('td_edit_cell')
        this.editing_el.removeClass(modals.uc)
        this.editing_el = false
    },

    getItemById: function(id){
        res = false
        
        for (key in this.current_data){

            if (this.current_data[key].id.value == id){
                res = this.current_data[key]
                break
            }

        }

        return res
    }
    
}
