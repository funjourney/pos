import ApplicationLogo from '@/Components/ApplicationLogo';
import Dropdown from '@/Components/Dropdown';
import NavLink from '@/Components/NavLink';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink';
import "bootstrap/dist/css/bootstrap.min.css";
import { Link, usePage, useForm } from '@inertiajs/react';
import { PropsWithChildren, ReactNode, useState } from 'react';

export default function AuthenticatedCustom({
    header,
    children,
}: PropsWithChildren<{ header?: ReactNode }>) {
    const user = usePage().props.auth.user;

    const { post } = useForm();

    function handleLogout(e: { preventDefault: () => void; }) {
        e.preventDefault();
        post(route('logout'));
    }

    const [showingNavigationDropdown, setShowingNavigationDropdown] =
        useState(false);

    return (
        <div className="min-h-screen bg-gray-100">
            {header && (
                <header className="bg-white text-white p-2">
                    <div className="container-fluid d-flex justify-content-between align-items-center px-2">
                        <div className="d-flex align-items-center gap-2">
                            <img 
                                src="https://o2.funjourney.co.id/assets/images/sites/FJ_logo.png" 
                                alt="Fun Journey Logo"
                                className="h-20 w-20"
                            />
                            <h2 className="text-secondary border-secondary ms-3">
                                {usePage().component}
                            </h2>
                        </div>
                        <div className="d-flex align-items-center gap-3 ms-auto">
                            <a className="btn btn-light">{user.name ?? "-"}</a>
                            <a href="#" onClick={() => window.history.back()} className="btn btn-secondary">
                                ← Back to Previous Page
                            </a>
                            <form onSubmit={handleLogout} className="d-inline">
                                <button type="submit" className="btn btn-danger">
                                    🚪 Logout
                                </button>
                            </form>
                            {user.role === "user" && (
                                <Dropdown>
                                    <Dropdown.Trigger>
                                        <button type="button" className="btn btn-light d-flex align-items-center">
                                            {user.name ?? "-"}
                                            <svg className="ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fillRule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clipRule="evenodd" />
                                            </svg>
                                        </button>
                                    </Dropdown.Trigger>
                                    <Dropdown.Content>
                                        <Dropdown.Link href={route('profile.edit')}>Profile</Dropdown.Link>
                                        <Dropdown.Link href={route('logout')} method="post" as="button">Log Out</Dropdown.Link>
                                    </Dropdown.Content>
                                </Dropdown>
                            )}
                        </div>
                    </div>
                </header>
            )}
            <main>{children}</main>
        </div>
    );        
}
