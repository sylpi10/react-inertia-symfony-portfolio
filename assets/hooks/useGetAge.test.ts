import { afterEach, describe, expect, it, vi } from "vitest";
import useGetAge from "./useGetAge";

// « aujourd'hui » figé : le résultat ne dépend plus du jour où le test tourne
function today(date: string) {
    vi.useFakeTimers();
    vi.setSystemTime(new Date(date));
}

// l'horloge redevient normale après chaque test
afterEach(() => {
    vi.useRealTimers();
});

/**
on teste le calcul de l'age avec ces 2 conditions:
month < birthDateObj.getMonth() ||
(month === birthDateObj.getMonth() && day < birthDateObj.getDate())
*/
describe("useGetAge", () => {
    it("on est avant l'anniversaire, le même mois de naissance", () => {
        today("2026-03-16");

        expect(useGetAge("1990-03-17")).toBe(35);
    });

    it("on est après l'anniversaire, le même mois de naissance", () => {
        today("2026-03-18");

        expect(useGetAge("1990-03-17")).toBe(36);
    });

    it("on est avant l'anniversaire, pas le mois de naissance ", () => {
        today("2026-01-16");

        expect(useGetAge("1990-03-17")).toBe(35);
    });

    it("on est après l'anniversaire, pas le même mois de naissance", () => {
        today("2026-08-18");

        expect(useGetAge("1990-03-17")).toBe(36);
    });

    it("anniversaire aujourd'hui", () => {
        today("2026-03-17");

        expect(useGetAge("1990-03-17")).toBe(36);
    });
});
