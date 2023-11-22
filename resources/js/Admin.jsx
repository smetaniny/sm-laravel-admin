import dataProvider from "./dataProvider.jsx";

const App = () => {
    console.log('dataProvider', dataProvider);
    return (
        <Admin
            dataProvider={dataProvider}
        >
        </Admin>
    );
};


ReactDOM.render(<App />, document.getElementById('app'));
