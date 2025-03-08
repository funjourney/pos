import axios, { AxiosRequestConfig } from 'axios';
 
 export class HttpRequest {
     private baseUrl: string;
 
     constructor() {
         this.baseUrl = import.meta.env.VITE_API_BASE_URL || '';
         axios.defaults.withCredentials = true; // Mengaktifkan credentials untuk CSRF token
         axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
     }
 
     private async request<T>(method: string, endpoint: string, data?: any, isFormData: boolean = false): Promise<T> {
         try {
             const authToken = localStorage.getItem("authToken");
             const headers: Record<string, string> = {};
             
             if (authToken) {
                 headers['Authorization'] = `Bearer ${authToken}`;
             }
             
             if (!isFormData) {
                 headers['Content-Type'] = 'application/json';
             }
 
             const config: AxiosRequestConfig = {
                 method,
                 url: `${this.baseUrl}${endpoint}`,
                 headers,
                 data,
             };
 
             if (method === 'GET' || method === 'DELETE') {
                 delete config.data;
             }
 
             // Ambil CSRF Token sebelum request kecuali untuk GET
             if (method !== 'GET') {
                 await axios.get('/sanctum/csrf-cookie');
             }
 
             const response = await axios(config);
             return response.data;
         } catch (error: any) {
             console.error(`${method} request failed:`, error.response?.data || error.message);
             throw new Error(error.response?.data?.message || error.message);
         }
     }
 
     async GET<T>(endpoint: string): Promise<T> {
         return this.request<T>('GET', endpoint);
     }
 
     async POST<T>(endpoint: string, data: any): Promise<T> {
         return this.request<T>('POST', endpoint, data, data instanceof FormData);
     }
 
     async PUT<T>(endpoint: string, data: any): Promise<T> {
         return this.request<T>('PUT', endpoint, data, data instanceof FormData);
     }
 
     async DELETE<T>(endpoint: string): Promise<T> {
         return this.request<T>('DELETE', endpoint);
     }
 }