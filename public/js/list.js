var list = {
	getInput: function(item, value = 0){

        if (item.is_connect){
            items = item.connect_model_list
        }else{
            items = this.processingString(item.items)
        }
     
        if (item.multiple){
            l = new Array()
            if (value){
                value.forEach(element => {
                    l.push(element.value)
                })
            }
            
            default_title = item.title_mn
            select = templates.getSelectMultipleTmpl({
                name: item.name,
                changeaction: 'helpers.simpleaction',
                title: item.title,
                default_value: l,
                default_title: 'Выберите '+default_title,
                data: items
            })
        }else{
            default_title = item.title
            select = templates.getSelectTmpl({
                name: item.name,
                changeaction: 'helpers.simpleaction',
                title: item.title,
                default_value: value,
                default_title: 'Выберите '+default_title,
                data: items
            })
        }

        _class='id'+app.getUniqueClass('select')
        select.addClass(_class)

        if (item.is_connect){
            //this.loadListItems(item, _class)
        }

        return select

    },

    loadListItems: function(item, select){
        xhr.sendRequest('/content/fields/list/'+item.id, 'fillSelect', select, list)
    },

    processingString: function(list){
        items = list.split("\n")
        result = new Array()
        items.forEach(element => {
            result.push({
                value: element,
                title: element
            })
        })

        return result
    },

    fillSelect: function(s, data, select){

        target = $('.'+select).find('select')
        data.data.forEach(element => {
            option = templates.getSelectOption(element)
            target.append(option)
        });

    },

    getOutput: function(text, item_id = false, field_id = false){
  
        container = templates.getSelectOutputTmpl(text, item_id, field_id)
        
        return container.html()

    },

    getInputValue: function(value){
        return value
    },

    callback: function(field, field_html){
        return true
    }
}