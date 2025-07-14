import { Routes } from '@angular/router';
import { DashboardComponent } from './dashboard/dashboard';
import { LandingComponent } from './landing/landing';
import { AuthGuard } from './auth.guard';

export const routes: Routes = [
  { path: '', component: LandingComponent },
  { path: 'dashboard', component: DashboardComponent, canActivate: [AuthGuard] },
  { path: '**', redirectTo: '' }
];
