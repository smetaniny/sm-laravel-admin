import React, {useState} from 'react';
import {Grid} from "@mui/material";
import AceEditor from 'react-ace';
import 'ace-builds/src-noconflict/mode-json';
import 'ace-builds/src-noconflict/mode-php';
import 'ace-builds/src-noconflict/mode-javascript';
import 'ace-builds/src-noconflict/theme-tomorrow';
import {useController} from "react-hook-form";

const TvAceEditor = (props) => {
    const { el, source } = props;
    const { field } = useController({ name: source });
    // Обновление значения поля при изменении в AceEditor
    const handleChange = (event) => {
        field.onChange(event);
    };
    const settingJSON = JSON.parse(el.setting);

    return (
        <Grid item xs={12}>
            <AceEditor
                label={props.label}
                value={field.value}
                onChange={handleChange}
                mode={settingJSON && settingJSON.mode ? settingJSON.mode : 'php'}
                name={settingJSON?.name || ''}
                width={settingJSON?.width || '100%'}
                height={settingJSON?.height || '300px'}
                placeholder={settingJSON?.placeholder || ''}
                theme={settingJSON?.theme || 'tomorrow'}
                showPrintMargin={settingJSON?.showPrintMargin ?? true}
                showGutter={settingJSON?.showGutter ?? true}
                highlightActiveLine={settingJSON?.highlightActiveLine ?? true}
                fontSize={14}
                setOptions={{
                    enableBasicAutocompletion: true,
                    enableLiveAutocompletion: true,
                    enableSnippets: true,
                    showLineNumbers: true,
                    tabSize: 2,
                    useWorker: true,
                }}
            />
        </Grid>
    );
};




export default TvAceEditor;
