var	table = {

    current_table: false,

    addColumn: function(title, is_filter){
        console.log(title);
        $('.table-title', this.current_table).append(templates.getTableTHTmpl(title, is_filter))
        $('tr', this.current_table).not('.table-title').append(templates.getTableTDTmpl(''))

    },

    addRow: function(item){
        console.log(item)
        this.current_table.find('.table-title').after(templates.fillTableWithData({0: item}))
    },

    reDrawTable: function(){

        if (this.current_table){
            this.current_table.remove()
            this.current_table = templates.getTables(fields.current_model_fields)

            this.current_table.append(templates.fillTableWithData(content.current_data))

            app.content.append(this.current_table)
        }

    },

    editModeForCell: function(e){
        content.current_item = content.getItemById(e.targer.dataset.item_id)
        field = fields.getFieldById(e.target.dataset.field_id)
        field_html = fields.getInput(field, content.current_item[field.name].value)
        console.log(field_html.html());
        e.target.innerHTML = field_html.html()
    }

}