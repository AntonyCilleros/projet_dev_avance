import axios from "axios";

export function printError(action, response="pas de détails") {
    console.error(`Erreur "${action}": `, response.data?.message ? response.data.message : response);
}