export class ExecuteLocalStorage {
    private localStorageKey: string;

    constructor() {
        this.localStorageKey = "myLocalStorageData";
    }

    async WriteJSON(key: string, data: any): Promise<void> {
        try {
            const dataString = JSON.stringify(data);
            localStorage.setItem(key, dataString);
            console.log(`Data JSON berhasil ditulis ke localStorage dengan kunci: ${key}`);
        } catch (error) {
            console.error(`Gagal menulis data JSON ke localStorage dengan kunci: ${key}`, error);
        }
    }

    async ReadJSON<T>(key: string): Promise<T | null> {
        try {
            const storedDataString = localStorage.getItem(key);
            if (storedDataString) {
                const storedJSON: T = JSON.parse(storedDataString);
                console.log(`Data JSON berhasil dibaca dari localStorage dengan kunci: ${key}`, storedJSON);
                return storedJSON;
            } else {
                console.log(`Tidak ada data JSON yang ditemukan di localStorage dengan kunci: ${key}`);
                return null;
            }
        } catch (error) {
            console.error(`Gagal membaca data JSON dari localStorage dengan kunci: ${key}`, error);
            return null;
        }
    }

    async WriteBuffer(key: string, buffer: ArrayBuffer): Promise<void> {
        try {
            localStorage.setItem(key, JSON.stringify(Array.from(new Uint8Array(buffer))));
            console.log(`Buffer berhasil ditulis ke localStorage dengan kunci: ${key}`);
        } catch (error) {
            console.error("Gagal menulis buffer ke localStorage:", error);
        }
    }

    async ReadBuffer(key: string): Promise<ArrayBuffer | null> {
        try {
            const storedDataString = localStorage.getItem(key);
            if (storedDataString) {
                const storedBuffer = Uint8Array.from(JSON.parse(storedDataString));
                console.log(`Buffer berhasil dibaca dari localStorage dengan kunci: ${key}`, storedBuffer);
                return storedBuffer.buffer;
            } else {
                console.log(`Tidak ada buffer yang ditemukan di localStorage dengan kunci: ${key}`);
                return null;
            }
        } catch (error) {
            console.error(`Gagal membaca buffer dari localStorage dengan kunci: ${key}`, error);
            return null;
        }
    }

    DeleteByKey(key: string): void {
        try {
            localStorage.removeItem(key);
            console.log(`Data berhasil dihapus dari localStorage dengan kunci: ${key}`);
        } catch (error) {
            console.error(`Gagal menghapus data dari localStorage dengan kunci: ${key}`, error);
        }
    }
}