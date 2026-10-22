import "@inertiajs/core";

declare module "@inertiajs/core" {
    export interface InertiaConfig {
        // props envoyées à toutes les pages (InertiaShareListener)
        sharedPageProps: {
            publicEmail: string;
        };
        flashDataType: {
            success?: string;
            error?: string;
        };
    }
}
