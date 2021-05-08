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
        timeReg = {time: [] for time in range(1, 22)}
        timeCount = {time: 0 for time in range(1, 22)}
        # {user1:count1, user2:count2, ...}
        prefCount = {user: len(users[user]) for user in users}

        # initiate timeReg
        for u in users:
            for t in users[u]:
                timeReg[t].append(u)
        # initiate timeCount
        for p in providers:
            for t in providers[p]:
                timeCount[t] += 1

        for _ in range(len(prefCount)):
            # find a user with the least preferences/choices
            user = min(prefCount, key=prefCount.get)
            prefCount[user] = math.inf
            if prefCount[user] > 0:
                i = 0
                # find a time slot
                while timeCount[users[user][i]] == 0:
                    i += 1
                timeslot = users[user][i]
                # find a provider
                for p in providers:
                    if timeslot in providers[p]:
                        provider = p
                # update timeCount
                timeCount[timeslot] -= 1
                # update prefCount optionally
                if timeCount[timeslot] == 0:
                    for u in timeReg[timeslot]:
                        prefCount[u] -= 1

                wid = (timeslot - 1) // 3 + 1
                tid = (timeslot - 1) % 3 + 1
                self.result[str(user)] = {"provider_id": provider, "wid": wid, "tid": tid}

            self.output_file()

    def output_file(self):
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
            provider: [timeslot]
        """
        with open("pat_rows.json") as pat_file:
            pat_data = json.load(pat_file)
        for pat in pat_data:
            provider_id, wid, tid = int(pat['provider_id']), int(pat['wid']), int(pat['tid'])
            if provider_id not in self.providers:
                self.providers[provider_id] = []
            self.providers[provider_id].append(3 * (wid - 1) + tid)

if __name__ == '__main__':
    users = {
        1: [1, 3, 4],
        2: [1],
        3: [3, 5],
        4: [4]
    }
    providers = {
        1: [1],
        3: [3],
        4: [4],
        5: [5]
    }
    schedule = Schedule(testdata=True)
    # {2: 1, 4: 4, 1: 3, 3: 5}
    schedule.assign(users, providers)
