@verbatim
	<head>
    <link rel="stylesheet" href="/css/styles.css" />
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200&display=swap" rel="stylesheet">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="/js/jtmpl.js"></script>
        <script src="/js/jquery.cookies.js"></script>
        <script src="/js/models.js"></script>
        <script src="/js/auth.js"></script>
        <script src="/js/menu.js"></script>
        <script src="/js/xhr.js"></script>
        <script src="/js/app.js"></script>
        <script src="/js/content.js"></script>
        <script src="/js/modals.js"></script>
        <script src="/js/checkbox.js"></script>
        <script src="/js/string.js"></script>
        <script src="/js/text.js"></script>
        <script src="/js/list.js"></script>
        <script src="/js/multiple.js"></script>
        <script src="/js/forms.js"></script>
        <script src="/js/helpers.js"></script>
        <script src="/js/templates.js"></script>
        <script src="/js/table.js"></script>
        <script src="/js/parent_id.js"></script>
        <script src="/js/fields.js"></script>
        <script src="/js/filter.js"></script>
    </head>
<body>
    <div class="container">
        <div class="sidebar">
            <a class="logo"></a>
        </div>

        <div class="content">
            <div class="buttons-container"></div>
            <div class="content-container"></div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            app.init()
        })
    </script>
</body>

    <script id="filterTmpl" type="text/x-jquery-tmpl">
        <div class="filter-panel">
            <div class="row">
            {{tmpl({
                        title: 'Содержит',
                        type: 'text',
                        name: 'value',
                        placeholder: 'Введите текст для поиска',
                        changeaction: 'filter.search'
                    }) '#inputTmpl'}}
            </div>
        </div>
    </script>

    <script id="menuTmpl" type="text/x-jquery-tmpl">
        <ul class="menu">
            <li class="button-li">
                <button class="btn" onclick="models.add(event)">Добавить раздел</button>
            </li>
            {{each(index, item) items}}
                {{tmpl({item: item}) '#menuItemTmpl'}}
            {{/each}}
        </ul>
    </script>

    <script id="menuItemTmpl" type="text/x-jquery-tmpl">
            {{if item.in_menu}}
                <li><a onclick="menu.navigate('${item.name}', $(this))" href="#">${item.title}</a></li>
            {{/if}}
    </script>

    <script id="addModelTmpl" type="text/x-jquery-tmpl">
        <div class="add-model-form">
            <form>
                <div class="row">
                    {{tmpl({
                        title: 'Название раздела',
                        type: 'text',
                        name: 'title',
                        placeholder: 'Введите название'
                    }) '#inputTmpl'}}

                    {{tmpl({
                        title: 'Название в винительном падеже',
                        type: 'text',
                        name: 'title2',
                        placeholder: 'Введите название в родительском падеже'
                    }) '#inputTmpl'}}
                </div>
                <div>
                    {{tmpl({
                        title: 'Системное имя(на английском)',
                        type: 'text',
                        name: 'name',
                        placeholder: 'Введите имя'
                    }) '#inputTmpl'}}
                </div>
                <br>
                <div class="row">
                    {{tmpl({
                        title: 'Видят все',
                        type: 'checkbox',
                        name: 'is_public'
                    }) '#inputTmpl'}}

                    {{tmpl({
                        title: 'Показывать в меню',
                        type: 'checkbox',
                        name: 'in_menu'
                    }) '#inputTmpl'}}
                </div>

                <div class="form-buttons">
                    {{tmpl({
                           clickaction: "models.saveModel",
                           text: 'Сохранить'
                    }) '#btnTmpl'}}

                    {{tmpl({
                        clickaction: "modals.closeModal",
                        text: 'Отмена'
                    }) '#btnTmpl'}}
                </div>
            </form>
        </div>
    </script>        

    <script id="addFormTmpl" type="text/x-jquery-tmpl">
        <div class="add-field-form">
            <form>
                <div class="row">
                    {{tmpl({
                        title: 'Название поля',
                        type: 'text',
                        name: 'title',
                        placeholder: 'Введите название'
                    }) '#inputTmpl'}}

                    {{tmpl({
                        title: 'Системное имя (на английском)',
                        type: 'text',
                        name: 'name',
                        placeholder: 'Введите имя'
                    }) '#inputTmpl'}}
                </div>
                
                <div class="row">
                    {{tmpl({
                        title: 'Обязательное поле',
                        type: 'checkbox',
                        name: 'is_require'
                    }) '#inputTmpl'}}

                    {{tmpl({
                        title: 'Включить фильтр',
                        type: 'checkbox',
                        name: 'in_filter'
                    }) '#inputTmpl'}}

                    {{tmpl({
                        title: 'Показать столбец в таблице',
                        type: 'checkbox',
                        name: 'is_show'
                    }) '#inputTmpl'}}
                </div>

                

                <div class="row">
                    {{tmpl({
                        title: 'Тип поля',
                        default_value: 0,
                        default_title: 'Выберите тип',
                        changeaction: 'fields.setFieldSettings',
                        name: 'type',
                        data: [
                            {
                                title: 'Строковое поле',
                                value: 'varchar'
                            },
                            {
                                title: 'Текстовое поле',
                                value: 'text'
                            },
                            {
                                title: 'Флаг',
                                value: 'checkbox'
                            },
                            {
                                title: 'Список',
                                value: 'list'
                            }
                        ]
                    }) '#selectTmpl'}}
                </div>

                <div class="field-settings">
                    <div>
                        {{tmpl({
                            name: 'list',
                            placeholder: 'Введите список',
                        }) '#textareaTmpl'}}

                        {{tmpl({
                            title: 'Импортировать из таблицы',
                            default_value: 0,
                            default_title: 'Без импорта',
                            changeaction: '',
                            name: 'import_model',
                            data: models.models
                        }) '#selectTmpl'}}

                        {{tmpl({
                            title: 'Связать с таблицей',
                            default_value: 0,
                            default_title: 'Без связки',
                            changeaction: '',
                            name: 'connect_model',
                            data: models.models
                        }) '#selectTmpl'}}
                    </div>

                    <div>
                        {{tmpl({
                            title: 'Множественный выбор',
                            type: 'checkbox',
                            name: 'multiple'
                        }) '#inputTmpl'}}

                        {{tmpl({
                            title: 'Название поля во множественном числе',
                            type: 'text',
                            name: 'title_mn',
                            placeholder: 'Введите название'
                        }) '#inputTmpl'}}

                        {{tmpl({
                            title: 'Название поля в родительном падеже',
                            type: 'text',
                            name: 'title2',
                            placeholder: 'Введите название'
                        }) '#inputTmpl'}}
                    </div>
                </div>

                <div class="form-buttons">
                    {{tmpl({
                           clickaction: "fields.saveField",
                           text: 'Сохранить'
                    }) '#btnTmpl'}} 

                    {{tmpl({
                        clickaction: "modals.closeModal",
                        text: 'Отмена'
                    }) '#btnTmpl'}}
                </div>
            </form>
        </div>
    </script>

    <script id="fieldsControlPanelTmpl" type="text/x-jquery-tmpl">
        <div class="fieldsControlPanel">
            <div class="fcp-list">
            {{each(index, field) list}}
                <div class="fcp-item">
                    <div>${field.title}</div>
                    <div>
                        {{if field.can_delete}}
                            <a class="btn delete" onclick="fields.deleteField(${field.id})"></a>
                        {{/if}}
                        {{if field.can_edit}}
                            <a class="btn edit" onclick="fields.editField(${field.id}, $(this))"></a>
                        {{/if}}
                        <a class="btn unshow visible-${field.is_show}" data-show="${field.is_show}" onclick="fields.toggleField(${field.id}, $(this))"></a>
                    </div>
                </div>
            {{/each}}
            </div>
        </div>
    </script>

    <script id="tableTHTmpl" type="text/x-jquery-tmpl">
        <th>
            ${title}
            {{if field.in_filter}}
                <a class="th-filter" onclick="filter.showFilterPanel('field', '${field.name}')"></a>
            {{/if}}
        </th>
    </script>

    <script id="tableTDTmpl" type="text/x-jquery-tmpl">
        <td>{{html html}}</td>
    </script>

    <script id="tableTmpl" type="text/x-jquery-tmpl">
        
        <table class="table">
            <tr class="table-title">
                {{each(index, field) fields}}
                    {{if field.is_show}}
                        <th class="${field.name} th-field-id-${field.id}">
                            ${field.title}
                            {{if field.in_filter}}
                                <a class="th-filter field-${field.name}" onclick="filter.showFilterPanel($(this), 'field', '${field.name}')"></a>
                            {{/if}}
                        </th>
                    {{/if}}
                {{/each}}
            </tr>
        </table>
    </script>

    <script id="tableItemsTmpl" type="text/x-jquery-tmpl">
        {{each(index, item) items}}
            <tr data-id="${item.id.value}" oncontextmenu="menu.showTrContextMenu(event, $(this))">
                {{each(ind, field) fields.current_model_fields}}
                    {{if field.is_show}}
                        <td ondblclick="content.initEditMode($(this));" data-field_id="${field.id}" data-item_id="${item.id.value}" class="td-field-id-${field.id}">
                            {{html fields.getOutput(item[field.name].type, item[field.name].value, item.id.value, field.id)}}
                        </td>
                    {{/if}}
                {{/each}}
            </tr>
        {{/each}}
    </script>

    <script id="checkboxOutputTmpl" type="text/x-jquery-tmpl">
        <div>
            <span>
                {{if value}}
                    Да
                {{else}}
                    Нет
                {{/if}}
            </span>
        </div>
    </script>

    <script id="selectOutputTmpl" type="text/x-jquery-tmpl">
        <div>
        {{if count > 1}}
        <div class="select-list multiple-select-list">
        {{else}}
        <div class="select-list">
        {{/if}}
            <ul>
                {{each(index, item) list}}
                    <li class="multiple-select-item-${item.value}">
                        <span>${item.title}</span>
                        <a href="#" class="unlink-list-item" onclick="content.unlinkModelElement($(this), ${item.value}, ${item_id}, ${field_id})">jn</a>
                    </li>
                {{/each}}
            </ul>
            {{if count > 1}}
                
                <a href="#" class="show-more-list-items" onclick="content.showMoreModelElement($(this))"><span class="msl-count">${count}</span></a>
            {{/if}}
        </div>
        </div>
    </script>

    <script id="selectTmpl" type="text/x-jquery-tmpl">
		<div class="field select">
            <label>${title}</label>
			<select class="js-select2" name="${name}" onchange="${changeaction}(event)">
				{{if default_title}}
					<option value="${default_value}">${default_title}</option>
			 	{{/if}}
				{{each(ind, item) data}}
					<option value="${item.value}">${item.title}</option>
				{{/each}}
			</select>
		</div>
	</script>

    <script id="textTmpl" type="text/x-jquery-tmpl">
        <span>${text}</span>
    </script>

    <script id="selectOptionsTmpl" type="text/x-jquery-tmpl">
		{{each(ind, item) data}}
			<option value="${item.value}">${item.title}</option>
		{{/each}}
	</script>

    <script id="selectOptionTmpl" type="text/x-jquery-tmpl">
		<option value="${item.value}">${item.title}</option>
	</script>

    <script id="selectMultipleTmpl" type="text/x-jquery-tmpl">
		<div class="field select">
            <label>${title}</label>
			<select multiple class="js-select2" name="${name}[]" onchange="${changeaction}(event)">
				{{if default_title}}
					<option value="${default_value}">${default_title}</option>
			 	{{/if}}
				{{each(ind, item) data}}
					<option 
                        {{if default_value.includes(item.value)}} 
                            selected="selected"
                        {{/if}} 
                        value="${item.value}">${item.title}</option>
				{{/each}}
			</select>
		</div>
	</script>
	
	<script id="selectMultipleTmpl1" type="text/x-jquery-tmpl">
        <div class="field select">
            <label>${title}</label>
            <div class="checkselect">
                {{each(ind, item) data}}
                    <label><input type="checkbox" name="${name}[]" value="${item.value}">${item.title}</label>
                {{/each}}
            </div>
		</div>
	</script>
	
    <script id="textareaTmpl" type="text/x-jquery-tmpl">
		<div class="field textarea">
			<textarea name="${name}" placeholder="${placeholder}"></textarea>
		</div>
	</script>

	<script id="input1Tmpl" type="text/x-jquery-tmpl">
		<div class="field input">
			<input type="${type}" name="${name}" onchange="${changeaction}(event)" value="${value}" placeholder="${placeholder}"/>
		</div>
	</script>

    <script id="inputTmpl" type="text/x-jquery-tmpl">
		<div class="field input ${type}">
            <label>${title}</label>
			<input type="${type}" name="${name}" onchange="${changeaction}(event)" value="${value}" placeholder="${placeholder}"/>
		</div>
	</script>
	
	<script id="checkboxTmpl" type="text/x-jquery-tmpl">
		<div class="field input">
			<input type="${type}" name="${name}" onchange="${changeaction}(event)" value="${value}" placeholder="${placeholder}"/>
		</div>
	</script>
	
	<script id="btnTmpl" type="text/x-jquery-tmpl">
		<button class="btn ${className}" onclick="${clickaction}(event)">
			{{html text}}
		</button>
	</script>

    <script id="popupContainerTmpl" type="text/x-jquery-tmpl">
        <div class="popup_container">
            {{tmpl '#popupContentTmpl'}}
        </div>
    </script>

    <script id="popupContentTmpl" type="text/x-jquery-tmpl">
        <div class="popup_content">
            {{html content}}
        </div>
    </script>

    <script id="loaderTmpl" type="text/x-jquery-tmpl">
        <div class="loader">
            загрузка..
        </div>
    </script>

@endverbatim