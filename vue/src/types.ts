export interface Rule {
  type: string;
  operator: string;
  value: string;
}

export interface Step {
  name: string;
  rules: Rule[];
  // Whether only the very next action after the step before counts.
  direct?: boolean;
}

export interface Funnel {
  idfunnel: number;
  idsite: number;
  name: string;
  description: string;
  steps: Step[];
  segment_filter: string;
  position: number;
  paused: boolean;
  created_date: string;
  updated_date: string;
}

export interface ArchiveStatus {
  idfunnel: number;
  first_date: string|null;
  last_date: string|null;
  is_queued: boolean;
  queued_days: number;
}

export interface StepResult {
  name: string;
  nb_visits: number;
  // null for the last step, which has no next step to go on to
  funnel_proceeded: number|null;
  funnel_exits: number|null;
  funnel_avg_step_time: number;
  // The revenue of the goal conversions and orders that reached the step.
  funnel_revenue?: number;
  // Leads to what the visits that left at the step did next, when that is archived.
  idsubtable?: number|null;
}

export interface NextAction {
  label: string;
  nb_visits: number;
}

// A funnel to start from in the editor.
export interface FunnelTemplate {
  name: string;
  steps: Step[];
}

// The same funnel for another period or segment.
export interface Comparison {
  label: string;
  steps: StepResult[];
}
