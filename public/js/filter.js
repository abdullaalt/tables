var filter = {

    by_field: false,
    value: false,
    filters: new Array(),
    current_filter_btn: false,

    showFilterPanel: function(e, source, name){
        this.by_field = name
        container = templates.getFilterPanel()

        coords = {
            left: e.offset().left-250,
            top: e.offset().top+20,
            width: 250
        }

        modal = modals.getModalsContainer(container, 'manual', false, true, coords)
        this.modal = modal

        key = this.getFilterKeyByFieldName(this.by_field)

        if (key){
            this.modal.find('input[name=value]').val(this.filters[key].value)
        }else{
            this.modal.find('input[name=value]').val('') 
        }
        this.current_filter_btn = e
        modals.show(modal, 'fields.resetEditMode')

    },

    search: function(e){

        this.addInFilter(e.target.value)
        this.sendSearchResult()
        
    },

    sendSearchResult: function(){
        fd = new FormData()
        //fd.append('filters', JSON.stringify(this.getFilter()))
        fd.append('filters', JSON.stringify(this.filters))
        fd.append('model', models.current_model.name)

        xhr.sendRequest('/content/search', "printData", null, content, 'POST', fd)

    },

    getFilter: function(){
        result = new Array();

        for (key in this.filters){
            if (this.filters[key].model == models.current_model){
                result.push({
                    by_field: this.filters[key].by_field,
                    model: models.current_model,
                    value: this.filters[key].value
                }) 
            }
        }

        return result
    },

    addInFilter: function(value){

        key = this.getFilterKeyByFieldName(this.by_field)
        if (!key){
            this.filters.push({
                by_field: this.by_field,
                model: models.current_model,
                value: value
            })
        }else{
            this.filters[key].value = value
        }

        console.log(this.filters);
        
    },

    getFilterKeyByFieldName: function(name){
        result = false
        for (key in this.filters){
            if (this.filters[key].by_field == this.by_field && this.filters[key].model == models.current_model){
                result = key
            }
        }
        return result
    },

    setResetBtns: function(){
        
        is_find = false
        for (key in this.filters){
            //if ($('.th-filter.field-'+this.filters[key].by_field).length > 0 && this.filters[key].model == models.current_model){
                $('.th-filter.field-'+this.filters[key].by_field).parent().append($('<a class="reset-filter" data-field="'+this.filters[key].by_field+'" onclick="filter.resetFilter($(this))">Сбросить</a>'))
                //is_find = true
            //}
        }

        //if (is_find) this.sendSearchResult()

    },

    resetFilter: function(e){

        key = this.getFilterKeyByFieldName(e.data('field'))
        console.log(key);
        this.filters.splice(key, 1)
        console.log(this.filters);
        e.remove()
        this.sendSearchResult()

    }

}