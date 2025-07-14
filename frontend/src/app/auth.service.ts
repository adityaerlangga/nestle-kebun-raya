import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { BehaviorSubject, Observable } from 'rxjs';
import { map, catchError } from 'rxjs/operators';
import { Router } from '@angular/router';
import { environment } from '../environments/environment';

export interface User {
  id: number;
  name: string;
  email: string;
  avatar?: string;
  google_id?: string;
}

export interface AuthResponse {
  success: boolean;
  user?: User;
  message?: string;
}

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private apiUrl = environment.apiUrl;
  private currentUserSubject = new BehaviorSubject<User | null>(null);
  public currentUser$ = this.currentUserSubject.asObservable();
  private authChecked = false;

  constructor(
    private http: HttpClient,
    private router: Router
  ) {
    // Don't check auth status immediately on service construction
    // Let components decide when to check
  }

  public get currentUserValue(): User | null {
    return this.currentUserSubject.value;
  }

  private getHttpOptions() {
    return {
      headers: new HttpHeaders({
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      }),
      withCredentials: true
    };
  }

  private checkAuthStatus(): Observable<boolean> {
    return this.http.get<AuthResponse>(`${this.apiUrl}/user`, this.getHttpOptions()).pipe(
      map(response => {
        if (response.success && response.user) {
          this.currentUserSubject.next(response.user);
          this.authChecked = true;
          return true;
        } else {
          this.currentUserSubject.next(null);
          this.authChecked = true;
          return false;
        }
      }),
      catchError(error => {
        this.currentUserSubject.next(null);
        this.authChecked = true;
        return [false];
      })
    );
  }

  loginWithGoogle(): void {
    window.location.href = `${this.apiUrl}/auth/google`;
  }

  logout(): Observable<AuthResponse> {
    // Use GET request instead of POST to avoid CSRF token issues
    return this.http.get<AuthResponse>(`${this.apiUrl}/logout`, this.getHttpOptions()).pipe(
      map(response => {
        if (response.success) {
          this.currentUserSubject.next(null);
          this.authChecked = false;
          this.router.navigate(['/']); // Redirect to landing page instead of login
        }
        return response;
      }),
      catchError(error => {
        // Even if the request fails, clear the local state and redirect
        this.currentUserSubject.next(null);
        this.authChecked = false;
        this.router.navigate(['/']); // Redirect to landing page instead of login
        return [{
          success: true,
          message: 'Logged out locally'
        }];
      })
    );
  }

  isAuthenticated(): boolean {
    return this.currentUserValue !== null;
  }

  // Method to check auth status when needed (called by components)
  checkAuth(): Observable<boolean> {
    if (!this.authChecked) {
      return this.checkAuthStatus();
    }
    return this.currentUser$.pipe(
      map(user => !!user)
    );
  }

  // Method to refresh auth status (useful after redirect)
  refreshAuthStatus(): void {
    this.authChecked = false;
    this.checkAuthStatus().subscribe();
  }
} 