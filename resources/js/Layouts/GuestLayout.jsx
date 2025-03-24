import ApplicationLogo from "@/Components/ApplicationLogo";
import { Link } from "@inertiajs/react";
import { GalleryVerticalEnd } from "lucide-react";

export default function GuestLayout({ children }) {
    return (
        <div className="flex min-h-svh items-center justify-center bg-muted p-6 md:p-10">
            <div className="grid w-full max-w-3xl overflow-hidden rounded-lg bg-white shadow-md md:grid-cols-2">
                {/* Left Side - Form Content */}
                <div className="p-6 md:p-10">{children}</div>

                {/* Right Side - Image */}
                <div className="hidden md:block">
                    <img
                        src="/img/solo_leveling1.jpeg"
                        alt="Auth Page Image"
                        className="h-full w-full object-cover"
                    />
                </div>
            </div>
        </div>
    );
}
