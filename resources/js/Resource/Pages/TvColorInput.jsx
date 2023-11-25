import React, {useReducer} from 'react';
import {useRecordContext} from "react-admin";
import {ColorInput} from "react-admin-color-picker";

const presetColors = [
    {value: '#282c34', label: '#006856 - Green'},
    {value: '#cd7fc4', label: '#cd7fc4 - Purple'},
    {value: '#a03b75', label: '#a03b75 - Crimson'},
    {value: '#00498d', label: '#00498d - Blue'},
    {value: '#bd3368', label: '#bd3368 - Crimson2'},
    {value: '#c7f394', label: '#c7f394 - Light Green'},
    {value: '#ff5c5c', label: '#ff5c5c - Red'},
    {value: '#ffffff', label: '#ffffff - White'},
    {value: '#000000', label: '#000000 - Black'},
    {value: '#ff6600', label: '#ff6600 - Orange'},
];



const TvColorInput = ({field, title}) => {
    const record = useRecordContext();

    // Инициализируем состояние цвета и флага применения цвета с помощью useReducer
    const [state, dispatch] = useReducer((prevState, action) => {
        switch (action.type) {
            case 'SET_COLOR':
                return {
                    ...prevState,
                    color: action.payload,
                };
            case 'TOGGLE_COLOR_ADD':
                return {
                    ...prevState,
                    colorAdd: !prevState.colorAdd,
                };
            default:
                return prevState;
        }
    }, {
        color: record.color || presetColors[0].value,
        colorAdd: false,
    });

    const handleColorChange = (color) => {
        dispatch({type: 'SET_COLOR', payload: color});
    };


    return (
        <div className="colorInput"
             style={{display: "flex", alignItems: "self-end"}}
        >
            <div
                style={{
                    width: "30px",
                    height: "30px",
                    marginBottom: 8,
                    backgroundColor: state.color
                }}
            />
            <ColorInput
                label={title}
                source={field}
                palette={presetColors}
                onChange={handleColorChange}
            />
        </div>
    );
};

export default TvColorInput;
