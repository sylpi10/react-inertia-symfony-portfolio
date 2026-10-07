import { Audience } from "./audience";

export type FormProps = {
    name: string;
    email: string;
    message: string;
    website: string;
    audience: Audience;
    // demande de devis : champs affichés en mode création de site uniquement
    projectType: string;
    budget: string;
    deadline: string;
};
