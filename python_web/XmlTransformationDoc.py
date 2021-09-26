#!/usr/bin/python3
from docxtpl import DocxTemplate,InlineImage
from docx.shared import Mm
import json,os
class XmlTransformationDoc(object):
    def __init__(self, path='demo.json',name='',template_path=''):
        self.path = path
        self.name = name
        self.template_path = template_path

    def start(self):
        tpl = DocxTemplate(self.template_path)
        zong_data = []
        info_text = ""
        with open(self.path,'r') as load_f:
            info_text = json.load(load_f)
        for a in range(0,len(info_text['alerts'])):
            data = {'name':"2."+str(a + 1)+" "+str(info_text['alerts'][a]['name']),'path':[]}
            for b in range(0, len(info_text['alerts'][a]['path'])):
                b_path = info_text['alerts'][a]['path'][b]
                verification = []
                bb_path = {'pathname':"2."+str(a + 1)+"."+str(b + 1)+" "+b_path['pathname'],'id': b_path['id'],'name': b_path['name'],'level': b_path['level'],'url': b_path['url'],'analysis': b_path['analysis'],'suggestions': b_path['suggestions'],}
                for c in range(0,len(b_path['verification'])):
                    if b_path['verification'][c]['type'] == 1:
                        verification.append(InlineImage(tpl, b_path['verification'][c]['text'], width=Mm(120)))
                    else:
                        verification.append(b_path['verification'][c]['text'])

                bb_path['verification'] = verification
                data['path'].append(bb_path)
            zong_data.append(data)

        data_top = {
            "name": info_text['name'],
            "time": info_text['time'],
            "producer": info_text['producer'],
            "producer_time": info_text['producer_time'],
            "reviewer": info_text['reviewer'],
            "reviewer_time": info_text['reviewer_time'],
            "hostlist": info_text['hostlist'],
            "risk_level": info_text['risk_level'],
            "url": info_text['url'],
            "alerts": zong_data,
            "serious": info_text['serious'],
            "low": info_text['low'],
            "medium": info_text['medium'],
            "high": info_text['high'],
            "vulnerability_types": info_text['vulnerability_types'],
        }
        tpl.render(data_top)
        file_path = os.path.abspath(os.path.dirname(__file__))+"/tmp/"+self.name+".docx"
        tpl.save(file_path)
        return self.name+".docx"