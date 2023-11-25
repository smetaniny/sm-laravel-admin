
createInertiaApp({
    resolve: (name) => resolvePageComponent(`./Admin.jsx`, import.meta.glob('./Admin.jsx')),
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(<App {...props} />);
    },
    progress: {
        color: '#4B5563',
    },
});
