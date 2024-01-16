var string = {

    getInput: function(item, value = ''){

        return templates.getInputTmpl({
            type: 'text',
            name: item.name,
            changeaction: 'helpers.simpleaction',
            value: value,
            placeholder: 'Введите '+item.title.toLowerCase(),
            title: item.title
        })

    },

    getOutput: function(text, item_id = false, field_id = false){

        container = templates.getVarcharTmpl(text)

        return container.html()

    },

    getInputValue: function(value){
        return value
    },

    callback: function(field, field_html){
        fieldInput = field_html.find('input')
        var fldLength= fieldInput.val().length;
        fieldInput.focus();
        fieldInput[0].setSelectionRange(fldLength, fldLength);
    }

}