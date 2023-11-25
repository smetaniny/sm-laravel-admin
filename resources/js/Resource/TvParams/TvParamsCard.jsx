import React from 'react';
import {
    AutocompleteInput,
    FormDataConsumer,
    NumberInput,
    ReferenceInput, required, SelectInput,
    TextInput,
} from "react-admin";
import {Card, CardContent, Grid, Tab, Tabs} from "@mui/material";
import AceEditor from 'react-ace';
import 'ace-builds/src-noconflict/mode-json';
import 'ace-builds/src-noconflict/theme-tomorrow';


const TvParamsCard = ({error, flag, setCode, code, validateForm}) => {

    const handleJsonChange = (newValue) => {
        setCode(newValue);
        // Активировать кнопку сохранения
    };

    const TextInputId = ({flag}) => {
        if (flag !== "create") {
            return <Grid item xs={12}>
                <TextInput
                    source="id"
                    label="Индитификатор"
                    disabled={true}
                    fullWidth
                /></Grid>
        } else {
            return null;
        }
    };
    return (
        <Card>
            <CardContent>
                <Tabs value={0} style={{
                    marginBottom: 30
                }}>
                    <Tab label="Общие" />
                </Tabs>
                <Grid item xs={12} sm={12}>
                    <Grid container spacing={2}>
                        <TextInputId flag={flag} />
                        <Grid item xs={12}>
                            <TextInput source="name" label="Имя параметра" fullWidth />
                            {error && <div className="error-text">{error.name}</div>}
                        </Grid>
                        {/*<Grid item xs={12}>*/}
                        {/*    <CodeInput value={``} onChange={handleCodeChange} />*/}
                        {/*</Grid>*/}
                        <Grid item xs={12}>
                            <TextInput source="caption" label="Заголовок параметра" fullWidth />
                        </Grid>
                        <Grid item xs={12}>
                            <TextInput source="description" label="Описание параметра" fullWidth />
                        </Grid>
                        <Grid item xs={12}>
                            <TextInput source="default_text"
                                       label="Значение по умолчанию (для текстовых параметров)" fullWidth />
                        </Grid>
                        <Grid item xs={12}>
                            <NumberInput source="default_number"
                                         label="Значение по умолчанию (для числовых параметров)" fullWidth />
                        </Grid>

                        <FormDataConsumer>
                            {({formData}) => {
                                return <>
                                    <Grid item xs={12}>
                                        <Grid item xs={12}>
                                            <ReferenceInput
                                                reference="templates"
                                                source="tv_param_template_id"
                                                defaultValue={formData.tv_param_template_id}
                                            >
                                                <AutocompleteInput
                                                    optionText="name"
                                                    label="Шаблон"
                                                />
                                            </ReferenceInput>
                                        </Grid>
                                    </Grid>
                                    <Grid item xs={12}>
                                        <Grid item xs={12}>
                                            <ReferenceInput
                                                reference="tv_param_categories"
                                                source="tv_param_category_id"
                                                defaultValue={formData.tv_param_category_id}
                                            >
                                                <AutocompleteInput
                                                    optionText="name"
                                                    label="Категории"
                                                />
                                            </ReferenceInput>
                                        </Grid>
                                    </Grid>
                                </>
                            }}
                        </FormDataConsumer>

                        <Grid item xs={12}>
                            <TextInput source="possible_values" label="Возможные значения" fullWidth />
                        </Grid>
                        <Grid item xs={12}>
                            <TextInput source="visual_component" label="Визуальный компонент" fullWidth />
                        </Grid>
                        <Grid item xs={12}>
                            <NumberInput source="order" label="Порядок в списке" fullWidth />
                        </Grid>
                        <Grid item xs={12}>
                            <SelectInput
                                source="input_type"
                                label="Тип ввода"
                                fullWidth
                                validate={required()}
                                choices={[
                                    {id: 'TextInput', name: 'TextInput - поле ввода текста'},
                                    {id: 'AutocompleteInput', name: 'AutocompleteInput - автозаполнение'},
                                    {id: 'CheckboxGroupInput', name: 'CheckboxGroupInput - группа флажков'},
                                    {id: 'ChoicesInput', name: 'ChoicesInput - ввод выбора'},
                                    {id: 'DateInput', name: 'DateInput - ввод даты'},
                                    {id: 'ImageInput', name: 'ImageInput - ввод изображения'},
                                    {id: 'LongTextInput', name: 'LongTextInput - многострочный ввод текста'},
                                    {id: 'BooleanInput', name: 'BooleanInput - ввод логического значения'},
                                    {
                                        id: 'NullableBooleanInput',
                                        name: 'NullableBooleanInput - ввод логического значения с возможностью нулевого значения'
                                    },
                                    {id: 'NumberInput', name: 'NumberInput - ввод числового значения'},
                                    {id: 'PasswordInput', name: 'PasswordInput - ввод пароля'},
                                    {
                                        id: 'RadioButtonGroupInput',
                                        name: 'RadioButtonGroupInput - группа радиокнопок'
                                    },
                                    {id: 'ReferenceInput', name: 'ReferenceInput - ввод ссылки'},
                                    {id: 'SearchInput', name: 'SearchInput - ввод поиска'},
                                    {id: 'ArrayInput', name: 'ArrayInput - ввод массива значений'},
                                    {
                                        id: 'AutocompleteArrayInput',
                                        name: 'AutocompleteArrayInput - автозаполнение для массива значений'
                                    },
                                    {
                                        id: 'ReferenceArrayInput',
                                        name: 'ReferenceArrayInput - ввод массива ссылок'
                                    },
                                    {id: 'SelectArrayInput', name: 'SelectArrayInput - ввод массива выбора'},
                                    {id: 'SelectInput', name: 'SelectInput - выпадающий список'},
                                    {id: 'Link', name: 'Link - ссылка'},
                                    {id: 'List', name: 'List - список'},
                                    {id: 'AceEditor', name: 'AceEditor - поле ввода кода'},
                                    {id: 'ColorInput', name: 'ColorInput - поле для цвета'},
                                ]}
                            />
                            {error && <div className="error-text">{error.input_type}</div>}
                        </Grid>

                        <FormDataConsumer>
                            {({formData}) => {
                                return <>
                                    <Grid item xs={12}>
                                        <AceEditor
                                            mode="json"
                                            source="setting"
                                            name="code-setting"
                                            width="100%"
                                            height="300px"
                                            placeholder="JSON-представление дополнительных параметров"
                                            theme="tomorrow"
                                            fontSize={14}
                                            showPrintMargin={true}
                                            showGutter={true}
                                            highlightActiveLine={true}
                                            setOptions={{
                                                enableBasicAutocompletion: true,
                                                enableLiveAutocompletion: true,
                                                enableSnippets: true,
                                                showLineNumbers: true,
                                                tabSize: 2,
                                                useWorker: true,
                                            }}
                                            onChange={handleJsonChange}
                                            defaultValue={formData.setting}
                                        />
                                    </Grid>
                                </>
                            }}
                        </FormDataConsumer>
                    </Grid>
                </Grid>
            </CardContent>
        </Card>
    )
};

export default TvParamsCard;
