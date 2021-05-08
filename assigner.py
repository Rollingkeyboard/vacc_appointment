import math
import json

class Schedule:
    def __init__(self, testdata=None):
        self.providers = {}
        self.users = {}
        self.result = {}
        if testdata is None:
            self.read_pat()
            self.read_ppt()

    def assign(self, users=None, providers=None):
        """
        Assign appointment from provider_available_time and patient_preferred_time
        """
        if users is None:
            users = self.users
        if providers is None:
            providers = self.providers
        # {time1: [user1, user2, ...], ...}
        time_reg = {time: [] for time in range(1, 22)}
        time_count = {time: 0 for time in range(1, 22)}
        # {user1:count1, user2:count2, ...}
        pref_count = {user: len(users[user]) for user in users}

        # initiate time_reg
        for u in users:
            for t in users[u]:
                time_reg[t].append(u)
        # initiate time_count
        for p in providers:
            for t in providers[p]:
                time_count[t[1]] += 1

        for _ in range(len(pref_count)):
            # find a user with the least preferences/choices
            user = min(pref_count, key=pref_count.get)
            count = pref_count[user]
            pref_count[user] = math.inf
            if count > 0:
                # find a time slot
                i = 0
                while time_count[users[user][i]] == 0:
                    i += 1
                timeslot = users[user][i]

                # find a provider
                for p in providers:
                    i = 0
                    # check this provider's available time
                    while i < len(providers[p]):
                        if providers[p][i][1] == timeslot:
                            provider = p
                            pat_id = providers[p][i][0]
                            break
                        i += 1
                    # found one
                    if i < len(providers[p]):
                        break

                # update time_count
                time_count[timeslot] -= 1

                # update pref_count optionally
                if time_count[timeslot] == 0:
                    for u in time_reg[timeslot]:
                        pref_count[u] -= 1

                wid = (timeslot - 1) // 3 + 1
                tid = (timeslot - 1) % 3 + 1
                self.result[str(user)] = {
                    "pat_id": pat_id,
                    "provider_id": provider,
                    "wid": wid,
                    "tid": tid
                }

            self.output_file()

    def output_file(self):
        """
        output the result to appointment.json
        """
        with open("appointment.json", "w") as appfile:
            appfile.write(json.dumps(self.result, indent=4))

    # patient_preferred_time
    def read_ppt(self):
        """
        turn list of
        {
            "ppt_id": "<ppt_id>",
            "patient_id": "<patient_id>",
            "wid": "<wid>",
            "tid": "<tid>"
        }
        into dict of
            user: [timeslot]
        """
        with open("ppt_rows.json") as ppt_file:
            ppt_data = json.load(ppt_file)
        for ppt in ppt_data:
            patient_id, wid, tid = int(ppt['patient_id']), int(ppt['wid']), int(ppt['tid'])
            if patient_id not in self.users:
                self.users[patient_id] = []
            self.users[patient_id].append(3 * (wid - 1) + tid)

    # provider_available_time
    def read_pat(self):
        """
        turn list of
        {
            "pat_id": "<pat_id>",
            "provider_id": "<provider_id>",
            "wid": "<wid>",
            "tid": "<tid>"
        }
        into dict of
            provider: [(pat_id, timeslot)]
            timeslot ranges from 1 to 21 inclusively
        """
        with open("pat_rows.json") as pat_file:
            pat_data = json.load(pat_file)
        for pat in pat_data:
            pat_id = int(pat['pat_id'])
            provider_id = int(pat['provider_id'])
            wid = int(pat['wid'])
            tid = int(pat['tid'])
            if provider_id not in self.providers:
                self.providers[provider_id] = []
            self.providers[provider_id].append((pat_id, 3 * (wid - 1) + tid))

if __name__ == '__main__':
    test_users = {
        1: [1, 4],
        2: [1],
        3: [3, 5],
        4: [4]
    }
    test_providers = {
        1: [(777, 1)],
        3: [(888, 3)],
        4: [(999, 4)],
        5: [(747, 5)]
    }
    schedule = Schedule(testdata=True)
    # {2: 1, 4: 4, 1: 3, 3: 5}
    schedule.assign(test_users, test_providers)
