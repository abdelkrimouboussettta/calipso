6.1.2.	Fichier __manifest__.py

Donne les informations d'identification du module et quelques paramètres, notamment relatifs à la possibilité pour l'utilisateur d'installer lui-même le module (installable), et au statut (application ou non) du module, qui détermine si le module apparaît dans la page principale des applications, sans devoir faire une recherche en enlevant le critère "Applications".

Contenu:

# -*- coding: utf-8 -*-
{
	"name": "Accesso Knowledge Base",
	"summary": "Accessolutions in-house Odoo Enterprise 11 - Knowledge base",
	"description": """
		Accessolutions in-house Odoo Enterprise 11 - Knowledge base
	""",
	"version": "2019.06.18",
	"depends": [],
	"author": "Cédric MOYNIEZ <cedric.moyniez@laposte.net>",
	"installable": True,
	"application": True,
	"data": [
		"views/kb_views.xml",
	],
	"demo": [
	],
	"qweb": [
	],
}

6.1.3.	Fichier models/__init__.py

Permet de créer le module Python models. Importe les deux modèles, et définit les constantes nécessaires à la recherche.

Contenu:

# coding: utf-8

from . import article
from . import tag
WEIGHT_INDEX=0.8 # Index of the term in the query expression
WEIGHT_TITLE = 5 # Location of the match: Article's title
WEIGHT_BODY = 1 # Location of the match: article's body
WEIGHT_TAG = 10 # Location of the match: Article's tags
WEIGHT_TAG_DERIVED = 2 # Location of the match: Derivative tags of the article's tags
WEIGHT_OCCUR_SPREAD = 1 # Number of occurrences - Logarithmic base
WEIGHT_OCCUR_ATTACK = 0.5 # Number of occurrences - Logarithmic offset 

6.1.4.	fichier models/article.py

Crée une classe Article, dont les instances sont sauvegardées en base automatiquement par l'ORM d'Odoo. Les champs sont définis au moyen des types de champs (Fields) définis par l'ORM. L'ORM crée lui-même les tables (table accesso_kb_article ici), les tables intermédiaires y compris (par exemple la table accesso_kb_article_accesso_kb_tag). L'ORM crée également automatiquement une sorte de nom à afficher dans les vues (string). Il s'agit, par défaut, du nom du champ dont la première lettre est mise en majuscule, avec d'autres changements pour les champs relationnels. Nous n'avons besoin de préciser l'attribut string que si ce comportement générique n'est pas satisfaisant. Le fichier contient aussi la méthode qui permet d'actualiser la date de validité de l'article. Le décorateur @api.multi signifie que la méthode peut être appelée de deux manières, celle de l'ancienne API de l'ORM et celle de la nouvelle API (qui attend beaucoup moins de paramètres).

Contenu:

# coding: utf-8

from odoo import models, fields, api

class Article(models.Model):
	"""Manage articles in the knowledge base"""
	_name = 'accesso_kb.article'
	title=fields.Char(required=True, index=True)
	content=fields.Text()
	valid_date=fields.Date(
		readonly="1",
		default=fields.Date.today()
	)
	valid_permanently=fields.Boolean(default=False)
	maintainer=fields.Many2one(
		"res.users",
		default=lambda self: self.env.user
	)
	tag_ids=fields.Many2many("accesso_kb.tag", string="Tags")
	
	@api.multi
	def is_valid(self):
		self.valid_date=fields.Date.today()

6.1.5.	Fichier models/tag.py

Même fonctionnement que pour le fichier précédent. Toutefois, la référence à des instances de la même classe, à des enregistrements de la même table nécessite d'être plus complet et plus explicite dans la définition des paramètres à passer au constructeur qu'un simple Many2many vers une autre classe.

Contenu:

# coding: utf-8

from odoo import models, fields, api

class Tag(models.Model):
	"""Manage articles' tags in the knowledge base"""
	_name = 'accesso_kb.tag'
	name=fields.Char(
		required=True,
		index=True,
	)
	parent_ids=fields.Many2many(
		comodel_name="accesso_kb.tag",
		relation="accesso_kb_tag_parents_rel",
		column1="tag_id",
		column2="parent_id",
		readonly="1",
		domain="[('synonym_id', 'ilike', self.id)]",
		string="Parents"
	)
	synonym_id=fields.Many2one(
		comodel_name="accesso_kb.tag",
		relation="accesso_kb_tag_synonym_rel",
		column1="tag_id",
		column2="synonym_id",
		string="Synonym"
	)
	
	_sql_constraints=[
		("name_unique", "UNIQUE(name)", "The tag name must be unique")
	]

6.1.6.	fichier views/kb_views.xml

Il s'agit de mettre en place les vues, puis les actions qui les utilisent, et enfin les menus qui déclenchent ces actions. Tout est considéré par Odoo comme étant des données, et les fichiers de données sous odoo sont au format xml.

Les vues reprennent, dans leur élément racine (form, tree ou kanban), les champs du modèle qu'elles doivent afficher, tout en y ajoutant des attributs, des conditions d'affichage...

Contenu:

<?xml version="1.0" encoding="utf-8"?>
<odoo>
	<record model="ir.ui.view" id="accesso_kb_article_tree_view">
		<field name="name">article.tree</field>
		<field name="model">accesso_kb.article</field>
		<field name="arch" type="xml">
			<tree>
				<field name="title"/>
				<field name="maintainer"/>
				<field name="tag_ids" widget="many2many_tags"/>
				<field name="valid_date"/>
			</tree>
		</field>
	</record>
	
	<record model="ir.ui.view" id="accesso_kb_article_kanban_view">
		<field name="name">article.kanban</field>
		<field name="model">accesso_kb.article</field>
		<field name="arch" type="xml">
			<kanban class="o_kanban_mobile">
				<templates>
					<t t-name="kanban-box">
						<div
							class="
								oe_kanban_global_click_edit
								oe_semantic_html_override
								oe_kanban_card
							"
						>
							<div class="oe_kanban_content">
								<h4>
									Title:
									<field name="title"/>
								</h4>
								Tags:
								<field name="tag_ids" widget="many2many_tags"/>
								<br/>
								Maintainer:
								<field name="maintainer"/>
								<br/>
								Validity date:
								<field name="valid_date"/>
								<br/>
							</div>
						</div>
					</t>
				</templates>
			</kanban>
		</field>
	</record>

	<record model="ir.ui.view" id="accesso_kb_article_form_view">
		<field name="name">article.form</field>
		<field name="model">accesso_kb.article</field>
		<field name="arch" type="xml">
			<form string="Articles">
				<sheet>
					<header attrs="{'invisible': [('valid_permanently', '=', True)]}">
						<button
							name="is_valid"
							type="object"
							string="Validate this article"
							class="btn-primary oe-highlight"
						/>
					</header>
					<group>
						<div class="oe_title">
							<h1>
								<field name="title" placeholder="Title"/>
							</h1>
						</div>
						<div class="oe_read_only">
							<t attrs="{'invisible': [('valid_permanently', '=', True)]}"> 
								<label for="valid_date">Validity date</label>
							</t>
							<t attrs="{'invisible': [('valid_permanently', '=', False)]}">
								<label for="valid_date">Valid permanently since the</label>
							</t>
							<field
								name="valid_date"
							/>
						</div>
						<div class="oe_edit_only">
							<field
								name="valid_permanently"
								string="Valid permanently"
							/>
						</div>
						<field
							name="tag_ids"
							widget="many2many_tags"
							options="{'create': True, 'create_edit': True}"
							string="Tags"
						/>
						<field name="maintainer" string="Maintainer"/>
						<field name="content" strinn="Content"/>
					</group>
				</sheet>
			</form>
		</field>
	</record>

	<record model="ir.actions.act_window" id="accesso_kb_articles_action">
		<field name="name">Articles</field>
		<field name="res_model">accesso_kb.article</field>
		<field name="view-type">kanban</field>
		<field name="view_mode">kanban,tree,form</field>
	</record>

	<menuitem id="accesso_kb_menu" name="Knowledge base"/>
	<menuitem
		parent="accesso_kb_menu"
		id="articles_menu"
		name="Articles"
		action="accesso_kb_articles_action"
	/>
	
	<record model="ir.ui.view" id="accesso_kb_tag_tree_view">
		<field name="name">tag.tree</field>
		<field name="model">accesso_kb.tag</field>
		<field name="arch" type="xml">
			<tree>
				<field name="name"/>
				<field name="parent_ids" widget="many2many_tags"/>
				<field name="synonym_id" widget="many2one_tag"/>
			</tree>
		</field>
	</record>
	
	<record model="ir.ui.view" id="accesso_kb_tag_form_view">
		<field name="name">tag.form</field>
		<field name="model">accesso_kb.tag</field>
		<field name="arch" type="xml">
			<form string="Tags">
				<sheet>
					<group>
						<div class="oe_title">
							<h1>
								<field name="name" placeholder="Name"/>
							</h1>
						</div>
						<field name="parent_ids" widget="many2many_tags"/>
						<field
							name="synonym_id"
							widget="many2one_tag"
							options="{'create': True, 'create_edit': True}"
						/>
					</group>
				</sheet>
			</form>
		</field>
	</record>
	
	<record model="ir.actions.act_window" id="accesso_kb_tags_action">
		<field name="name">Tags</field>
		<field name="res_model">accesso_kb.tag</field>
		<field name="view-type">tree</field>
		<field name="view_mode">tree,form</field>
	</record>
	
	<menuitem
		parent="accesso_kb_menu"
		id="tags_menu"
		name="Tags"
		action="accesso_kb_tags_action"
	/>
</odoo>

6.2.4.	models/call.py

Dans ce fichier, non seulement on crée la classe Call, mais on est aussi obligé de surcharger les méthodes create et write héritées de la classe models.Model: il faut que la note soit du bon type, et il faut la rattacher automatiquement au bon partenaire (indiquer le bon modèle et le bon enregistrement). Peu importe ce que l'utilisateur saisit dans le formulaire, ces données seront toujours écrasées: on ne peut pas modifier le modèle mail.message pour intégrer ces valeurs, parce qu'il est utilisé dans de nombreux contextes où ce comportement n'est pas souhaitable.

from odoo import models, fields, api

class Call(models.Model):
	_name="accesso_phone.call"
	_order="timestamp desc"
	type=fields.Selection(
		selection=[
			("Internal", "Internal"),
			("Inbound", "Inbound"),
			("Outbound", "Outbound")
		]
	)
	user_id=fields.Many2one(
		"res.users",
		default=lambda self: self.env.user,
		string="User")
	user_phone=fields.Char("User (Phone Number)")
	timestamp=fields.Datetime(
		default=fields.Datetime.now(),
		string="Date and time")
	duration=fields.Float()
	partner_id=fields.Many2one("res.partner", string="Partner")
	partner_phone=fields.Char(string="Partner (phone)")
	source_id=fields.Many2one("res.users", string="Source")
	source_phone=fields.Char(string="Source (phone number)")
	destination_id=fields.Many2one("res.users", string="Destination")
	destination_phone=fields.Char(string="Destination (phone number)")
	subject=fields.Char()
	note_id=fields.Many2one(
		"mail.message",
		string="Note"
	)
	userAgent=fields.Char(string="User Agent")
	
	@api.model
	def create(self,values):
		call_create = super(Call,self).create(values)
		if call_create.note_id:
			call_create.note_id.message_type="notification"
			call_create.note_id.model="res.partner"
			call_create.note_id.res_id=call_create.partner_id.id
		
		return call_create
	
	@api.multi
	def write(self,values):
		call_write = super(Call, self).write(values)
		if call_create.note_id:
			call_create.note_id.message_type="notification"
			call_create.note_id.model="res_partner"
			call_create.note_id.res_id=call_create.partner_id.id
		
		return call_write

6.2.5.	Fichier models.rule

Ce fichier servira, à terme, à automatiser la mise à jour d'Accessphone en implantant de nouvelles règles de qualification des appels. Nous ne nous en servons pas dans le reste du module, mais il reste nécessaire dans le cadre du lien entre Odoo et Accessphone.

Contenu:

from odoo import models, fields, api

class Rule(models.Model):
	_name="accesso_phone.rule"
	version=fields.Char()
	config=fields.OrderedDict(string="Configuration")

6.2.6.	Fichier views/phone_views.xml

<?xml version="1.0" encoding="utf-8"?>
<odoo>
	<record model="ir.ui.view" id="accesso_phone_call_tree_view">
		<field name="name">call.tree</field>
		<field name="model">accesso_phone.call</field>
		<field name="arch" type="xml">
			<tree string="Call tracking: list view">
				<field name="type"/>
				<field name="user_id"/>
				<field name="timestamp"/>
				<field name="duration"/>
				<field name="partner_id"/>
				<field name="source_id"/>
				<field name="destination_id"/>
				<field name="subject"/>
			</tree>
		</field>
	</record>
	
	<record model="ir.ui.view" id="accesso_phone_call_search_view">
		<field name="name">call.search</field>
		<field name="model">accesso_phone.call</field>
		<field name="arch" type="xml">
			<search string="Call tracking: search">
				<field
					name="timestamp"
					filter_domain="[('timestamp', 'ilike', fields.Date.today())]"
				/>
				<field name="user_id"/>
				<field
					name="source_id"
					filter_domain="[
						'|',
						'|',
						('user_id', 'ilike', self),
						('source_id', 'ilike', self),
						('destination_id', 'ilike', self)
					]"
					string="All users"
				/>
				<field
					name="partner_id"
					filter_domain="[
						'|',
						('partner_id', 'ilike', self),
						('partner_phone', 'ilike', self)
					]"
					string="Partner"
				/>
			</search>
		</field>
	</record>
	
	<record model="ir.ui.view" id="accesso_phone_call_form_view">
		<field name="name">call.form</field>
		<field name="model">accesso_phone.call</field>
		<field name="arch" type="xml">
			<form>
				<sheet>
					<group>
						<h1 attrs="{'invisible': [('id', '!=', False)]}" class="oe_edit_only">Add a call</h1>
						<h1 attrs="{'invisible': [('id', '=', False)]}" class="oe_edit_only">Edit a call</h1>
						<h1 class="oe_read_only">Call</h1>
						<field
							name="type"
							attrs="{
								'readonly': [
									('id', '!=', False),
									('userAgent', '!=', False)
								]
							}"/>
						<field
							name="user_id"
							attrs="{
								'readonly': [
									('id', '!=', False),
									('userAgent', '!=', False)
								]
							}"/>
						<field
							name="user_phone"
							attrs="{
								'readonly': [
									('id', '!=', False),
									('userAgent', '!=', False)
								]
							}"/>
						<field
							name="timestamp"
							attrs="{
								'readonly': [
									('id', '!=', False),
									('userAgent', '!=', False)
								]
							}"/>
						<field
							name="duration"
							attrs="{
								'readonly': [
									('id', '!=', False),
									('userAgent', '!=', False)
								]
							}"/>
						<field
							name="partner_id"
							attrs="{
								'readonly': [
									('id', '!=', False),
									('userAgent', '!=', False)
								]
							}"/>
						<field
							name="partner_phone"
							attrs="{
								'readonly': [
									('id', '!=', False),
									('userAgent', '!=', False)
								]
							}"/>
						<field
							name="source_id"
							attrs="{
								'readonly': [
									('id', '!=', False),
									('userAgent', '!=', False)
								]
							}"/>
						<field
							name="source_phone"
							attrs="{
								'readonly': [
									('id', '!=', False),
									('userAgent', '!=', False)
								]
							}"/>
						<field
							name="destination_id"
							attrs="{
								'readonly': [
									('id', '!=', False),
									('userAgent', '!=', False)
								]
							}"/>
						<field
							name="destination_phone"
							attrs="{
								'readonly': [
									('id', '!=', False),
									('userAgent', '!=', False)
								]
							}"/>
						<field name="subject"
							attrs="{
								'required': [
									('type', '!=', 'Internal'),
									('userAgent', '!=', False)
								]
							}"
						/>
						<field name="note_id" attrs="{'readonly': ['note_id', '!=', False]}"/>
						<field name="userAgent" groups="base.group_no_one"/>
					</group>
				</sheet>
			</form>
		</field>
	</record>
	
	<record model="ir.actions.act_window" id="accesso_phone_call_action">
		<field name="name">Calls</field>
		<field name="res_model">accesso_phone.call</field>
		<field name="view-type">tree</field>
		<field name="view_mode">tree,form</field>
	</record> 
	
	<menuitem id="menu_phone" name="Phone calls tracking"/>
	<menuitem
		id="menu_phone_call_list"
		parent="menu_phone"
		name="Calls"
		action="accesso_phone_call_action"
	/>
</odoo>