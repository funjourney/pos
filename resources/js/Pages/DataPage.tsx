import React, { useEffect, useState } from "react";
import { ExecuteLocalStorage } from "../tools/LocalStorage";
import { HttpRequest } from "../tools/HttpRequest";
import "bootstrap/dist/css/bootstrap.min.css"; // Import Bootstrap CSS

const localStorageHandler = new ExecuteLocalStorage();
const apiClient = new HttpRequest();

const LocalStoragePage: React.FC = () => {
    const [data, setData] = useState<string | null>(null);
    const [apiResponse, setApiResponse] = useState<any>(null);

    useEffect(() => {
        localStorageHandler.WriteJSON("testKey", { message: "Hello, LocalStorage!" });
        localStorageHandler
            .ReadJSON<{ message: string }>("testKey")
            .then((storedData) => {
                if (storedData) {
                    setData(storedData.message);
                }
            })
            .catch((error) => console.error("LocalStorage error:", error));

        handleGetRequest();
    }, []);

    const handleGetRequest = async () => {
        try {
            const response = await apiClient.GET<{ message: string }>("/api/data-page");
            setApiResponse(response);
        } catch (error: any) {
            console.error("API GET error:", error.message);
        }
    };

    const handlePostRequest = async () => {
        try {
            const formData = new FormData();
            formData.append("name", "John Doe");
        
            const response = await apiClient.POST<{ message: string }>("/api/data-page", formData);
            setApiResponse(response);
        } catch (error: any) {
            console.error("API POST error:", error.message);
        }
    };

    const handlePutRequest = async () => {
        try {
            const formData = new FormData();
            formData.append("name", "Updated Name");

            const response = await apiClient.PUT<{ message: string }>("/api/data-page/1", formData);
            setApiResponse(response);
        } catch (error: any) {
            console.error("API PUT error:", error.message);
        }
    };
    
    const handleDeleteRequest = async () => {
        try {
            const response = await apiClient.DELETE<{ message: string }>("/api/data-page/1");
            setApiResponse(response);
        } catch (error: any) {
            console.error("API DELETE error:", error.message);
        }
    };

    return (
        <div className="container mt-4">
            <h1>LocalStorage React Page</h1>
            <p>Data dari localStorage: {data || "Tidak ada data"}</p>
            <h2>API Response</h2>
            <pre>{apiResponse ? JSON.stringify(apiResponse, null, 2) : "Belum ada response"}</pre>
            
            <div className="mt-3">
                <button className="btn btn-primary m-2" onClick={handleGetRequest}>GET Data</button>
                <button className="btn btn-success m-2" onClick={handlePostRequest}>POST Data</button>
                <button className="btn btn-warning m-2" onClick={handlePutRequest}>PUT Data</button>
                <button className="btn btn-danger m-2" onClick={handleDeleteRequest}>DELETE Data</button>
            </div>
        </div>
    );
};

export default LocalStoragePage;
