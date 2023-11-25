import { create } from "zustand";
import { devtools } from "zustand/middleware";
import { immer } from "zustand/middleware/immer";

const loaderStore = create(
    devtools(
        immer((set, get) => ({
            // Изначальное значение для loading
            loading: true,

            setLoading: (value) => {
                set(() => ({ loading: value }));
            }
        }))
    )
);

export default loaderStore;
